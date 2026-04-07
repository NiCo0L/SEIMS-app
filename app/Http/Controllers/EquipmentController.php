<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCategory;
use App\Models\EquipmentDocument;
use App\Models\EquipmentItem;
use App\Models\EquipmentStatusLog;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        $equipment = EquipmentItem::with(['category', 'type'])
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('document_number', 'like', "%{$search}%")
                        ->orWhere('control_number', 'like', "%{$search}%")
                        ->orWhere('person_in_charge', 'like', "%{$search}%");
                });
            })
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['date_from'] ?? null, fn ($query, $dateFrom) => $query->whereDate('created_at', '>=', $dateFrom))
            ->when($filters['date_to'] ?? null, fn ($query, $dateTo) => $query->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('equipment.index', [
            'equipment' => $equipment,
            'filters' => $filters,
            'statusOptions' => EquipmentItem::statusOptions(),
            'categorySummary' => EquipmentCategory::withCount('equipmentItems')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('equipment.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $this->validatedEquipment($request);
        EquipmentItem::create($validated);

        return redirect()->route('equipment.index')->with('status', 'Equipment registered successfully.');
    }

    public function show(EquipmentItem $equipment)
    {
        $equipment->load(['category', 'type', 'statusLogs.user', 'documents.user']);

        return view('equipment.show', [
            'equipment' => $equipment,
            'statusOptions' => EquipmentItem::statusOptions(),
            'statusLogs' => $equipment->statusLogs()->with('user')->latest('status_date')->paginate(12, ['*'], 'history'),
            'documents' => $equipment->documents()->with('user')->latest()->paginate(10, ['*'], 'documents'),
        ]);
    }

    public function edit(EquipmentItem $equipment)
    {
        return view('equipment.edit', array_merge($this->formData(), ['equipment' => $equipment]));
    }

    public function update(Request $request, EquipmentItem $equipment)
    {
        $validated = $this->validatedEquipment($request);
        $equipment->update($validated);

        return redirect()->route('equipment.show', $equipment)->with('status', 'Equipment record updated successfully.');
    }

    public function status(Request $request, EquipmentItem $equipment)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:good,maintenance_pre,maintenance_post,transferred,condemned'],
            'status_date' => ['required', 'date'],
            'document_number' => ['required', 'string', 'max:100'],
            'remarks' => ['nullable', 'string'],
        ]);

        $equipment->update([
            'status' => $validated['status'],
            'remarks' => $validated['remarks'] ?? null,
            'last_status_date' => $validated['status_date'],
        ]);

        EquipmentStatusLog::create([
            'equipment_item_id' => $equipment->id,
            'user_id' => $request->user()->id,
            'status' => $validated['status'],
            'status_date' => $validated['status_date'],
            'document_number' => $validated['document_number'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()->route('equipment.show', $equipment)->with('status', 'Equipment status updated successfully.');
    }

    public function document(Request $request, EquipmentItem $equipment)
    {
        $validated = $request->validate([
            'document_type' => ['required', 'string', 'max:100'],
            'document_number' => ['nullable', 'string', 'max:100'],
            'attachment' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx', 'max:5120'],
            'remarks' => ['nullable', 'string'],
        ]);

        $file = $validated['attachment'];
        $path = $file->store('equipment-documents');

        EquipmentDocument::create([
            'equipment_item_id' => $equipment->id,
            'user_id' => $request->user()->id,
            'document_type' => $validated['document_type'],
            'document_number' => $validated['document_number'] ?? null,
            'original_name' => $file->getClientOriginalName(),
            'stored_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()->route('equipment.show', $equipment)->with('status', 'Document uploaded successfully.');
    }

    public function download(EquipmentDocument $document)
    {
        return Storage::download($document->stored_path, $document->original_name);
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        $equipment = EquipmentItem::with(['category', 'type'])
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('document_number', 'like', "%{$search}%")
                        ->orWhere('control_number', 'like', "%{$search}%")
                        ->orWhere('person_in_charge', 'like', "%{$search}%");
                });
            })
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['date_from'] ?? null, fn ($query, $dateFrom) => $query->whereDate('created_at', '>=', $dateFrom))
            ->when($filters['date_to'] ?? null, fn ($query, $dateTo) => $query->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->get();

        return response()->streamDownload(function () use ($equipment) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Equipment', 'Document No.', 'Control No.', 'Category', 'Type', 'Status', 'Person In Charge', 'Last Status Date', 'Remarks']);

            foreach ($equipment as $item) {
                fputcsv($handle, [
                    $item->name,
                    $item->document_number,
                    $item->control_number,
                    $item->category->name,
                    $item->type->name,
                    strtoupper(str_replace('_', ' ', $item->status)),
                    $item->person_in_charge,
                    $item->last_status_date?->format('Y-m-d'),
                    $item->remarks,
                ]);
            }

            fclose($handle);
        }, 'equipment-records.csv', ['Content-Type' => 'text/csv']);
    }

    private function validatedEquipment(Request $request): array
    {
        return $request->validate([
            'equipment_category_id' => ['required', 'integer', 'exists:equipment_categories,id'],
            'equipment_type_id' => ['required', 'integer', 'exists:equipment_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'document_number' => ['required', 'string', 'max:100'],
            'control_number' => ['required', 'string', 'max:100'],
            'person_in_charge' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:good,maintenance_pre,maintenance_post,transferred,condemned'],
            'last_status_date' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ]);
    }

    private function formData(): array
    {
        return [
            'categories' => EquipmentCategory::orderBy('name')->get(),
            'types' => EquipmentType::with('category')->orderBy('name')->get(),
            'statusOptions' => EquipmentItem::statusOptions(),
        ];
    }
}
