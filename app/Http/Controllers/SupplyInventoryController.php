<?php

namespace App\Http\Controllers;

use App\Models\SupplyCategory;
use App\Models\SupplyItem;
use App\Models\SupplyTransaction;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SupplyInventoryController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string'],
            'warehouse_id' => ['nullable', 'integer', 'exists:warehouses,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        $supplies = SupplyItem::with(['category', 'unit', 'warehouse'])
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('document_number', 'like', "%{$search}%")
                        ->orWhereHas('transactions', function ($transactionQuery) use ($search) {
                            $transactionQuery->where('recipient_name', 'like', "%{$search}%")
                                ->orWhere('person_in_charge', 'like', "%{$search}%")
                                ->orWhere('document_number', 'like', "%{$search}%");
                        });
                });
            })
            ->when($filters['warehouse_id'] ?? null, fn ($query, $warehouseId) => $query->where('warehouse_id', $warehouseId))
            ->when($filters['date_from'] ?? null, fn ($query, $dateFrom) => $query->whereDate('created_at', '>=', $dateFrom))
            ->when($filters['date_to'] ?? null, fn ($query, $dateTo) => $query->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('supplies.index', [
            'supplies' => $supplies,
            'filters' => $filters,
            'warehouses' => Warehouse::orderBy('name')->get(),
            'categorySummary' => SupplyCategory::withCount('supplyItems')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('supplies.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $this->validatedSupply($request);
        SupplyItem::create($validated);

        return redirect()->route('supplies.index')->with('status', 'Supply item created successfully.');
    }

    public function show(SupplyItem $supply)
    {
        $supply->load(['category', 'unit', 'warehouse', 'transactions.user']);

        return view('supplies.show', [
            'supply' => $supply,
            'transactions' => $supply->transactions()->with('user')->latest()->paginate(15),
        ]);
    }

    public function edit(SupplyItem $supply)
    {
        return view('supplies.edit', array_merge($this->formData(), ['supply' => $supply]));
    }

    public function update(Request $request, SupplyItem $supply)
    {
        $validated = $this->validatedSupply($request);
        $supply->update($validated);

        return redirect()->route('supplies.show', $supply)->with('status', 'Supply item updated successfully.');
    }

    public function transaction(Request $request, SupplyItem $supply)
    {
        $validated = $request->validate([
            'transaction_type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'document_number' => ['required', 'string', 'max:100'],
            'reference_date' => ['required', 'date'],
            'recipient_name' => ['nullable', 'string', 'max:255'],
            'person_in_charge' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
        ]);

        if ($validated['transaction_type'] === 'out' && $supply->current_quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Withdrawal quantity exceeds available stock.'])->withInput();
        }

        $before = (float) $supply->current_quantity;
        $after = $validated['transaction_type'] === 'in'
            ? $before + (float) $validated['quantity']
            : $before - (float) $validated['quantity'];

        $supply->update(['current_quantity' => $after]);

        SupplyTransaction::create([
            'supply_item_id' => $supply->id,
            'user_id' => $request->user()->id,
            'transaction_type' => $validated['transaction_type'],
            'quantity' => $validated['quantity'],
            'balance_before' => $before,
            'balance_after' => $after,
            'document_number' => $validated['document_number'],
            'reference_date' => $validated['reference_date'],
            'recipient_name' => $validated['recipient_name'] ?? null,
            'person_in_charge' => $validated['person_in_charge'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()->route('supplies.show', $supply)->with('status', 'Stock movement recorded successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string'],
            'warehouse_id' => ['nullable', 'integer', 'exists:warehouses,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        $transactions = SupplyTransaction::with(['supplyItem.category', 'supplyItem.unit', 'supplyItem.warehouse', 'user'])
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('document_number', 'like', "%{$search}%")
                        ->orWhere('recipient_name', 'like', "%{$search}%")
                        ->orWhereHas('supplyItem', fn ($supplyQuery) => $supplyQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($filters['warehouse_id'] ?? null, function ($query, $warehouseId) {
                $query->whereHas('supplyItem', fn ($supplyQuery) => $supplyQuery->where('warehouse_id', $warehouseId));
            })
            ->when($filters['date_from'] ?? null, fn ($query, $dateFrom) => $query->whereDate('reference_date', '>=', $dateFrom))
            ->when($filters['date_to'] ?? null, fn ($query, $dateTo) => $query->whereDate('reference_date', '<=', $dateTo))
            ->latest('reference_date')
            ->get();

        return response()->streamDownload(function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Item', 'Category', 'Warehouse', 'Unit', 'Type', 'Quantity', 'Before', 'After', 'Document No.', 'Recipient', 'Person In Charge', 'Recorded By', 'Remarks']);

            foreach ($transactions as $transaction) {
                fputcsv($handle, [
                    $transaction->reference_date?->format('Y-m-d'),
                    $transaction->supplyItem->name,
                    $transaction->supplyItem->category->name,
                    $transaction->supplyItem->warehouse->name,
                    $transaction->supplyItem->unit->name,
                    strtoupper($transaction->transaction_type),
                    $transaction->quantity,
                    $transaction->balance_before,
                    $transaction->balance_after,
                    $transaction->document_number,
                    $transaction->recipient_name,
                    $transaction->person_in_charge,
                    $transaction->user?->name,
                    $transaction->remarks,
                ]);
            }

            fclose($handle);
        }, 'supply-transactions.csv', ['Content-Type' => 'text/csv']);
    }

    private function validatedSupply(Request $request): array
    {
        return $request->validate([
            'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
            'supply_category_id' => ['required', 'integer', 'exists:supply_categories,id'],
            'unit_id' => ['required', 'integer', 'exists:units,id'],
            'name' => ['required', 'string', 'max:255'],
            'document_number' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'current_quantity' => ['required', 'numeric', 'min:0'],
            'minimum_quantity' => ['required', 'numeric', 'min:0'],
        ]);
    }

    private function formData(): array
    {
        return [
            'warehouses' => Warehouse::orderBy('name')->get(),
            'categories' => SupplyCategory::orderBy('name')->get(),
            'units' => Unit::orderBy('name')->get(),
        ];
    }
}
