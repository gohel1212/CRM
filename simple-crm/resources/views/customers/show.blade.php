@extends('layouts.app')

@section('title', $customer->name)

@section('actions')
    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">
        <i class="bi bi-pencil"></i> Edit Customer
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <!-- Customer Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Email:</strong> {{ $customer->email }}</p>
                            <p><strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}</p>
                            <p><strong>Website:</strong> {{ $customer->website ?? 'N/A' }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $customer->status === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($customer->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Address:</strong> {{ $customer->address ?? 'N/A' }}</p>
                            <p><strong>City:</strong> {{ $customer->city ?? 'N/A' }}</p>
                            <p><strong>State:</strong> {{ $customer->state ?? 'N/A' }}</p>
                            <p><strong>Country:</strong> {{ $customer->country ?? 'N/A' }}</p>
                            <p><strong>Postal Code:</strong> {{ $customer->postal_code ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @if($customer->description)
                        <div class="mt-3">
                            <strong>Description:</strong>
                            <p class="mt-2">{{ $customer->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Deals -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Deals</h5>
                    <a href="{{ route('deals.create', ['customer_id' => $customer->id]) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg"></i> Add Deal
                    </a>
                </div>
                <div class="card-body">
                    @if($customer->deals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Stage</th>
                                        <th>Expected Close</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->deals as $deal)
                                        <tr>
                                            <td>{{ $deal->name }}</td>
                                            <td>${{ number_format($deal->amount, 2) }}</td>
                                            <td>{{ optional($deal->stage)->name ?? 'N/A' }}</td>
                                            <td>{{ $deal->expected_close_date ? $deal->expected_close_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('deals.show', $deal) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No deals found.</p>
                    @endif
                </div>
            </div>

            <!-- Activities -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Activities</h5>
                    <a href="{{ route('activities.create', ['customer_id' => $customer->id]) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg"></i> Add Activity
                    </a>
                </div>
                <div class="card-body">
                    @if($customer->activities->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Type</th>
                                        <th>Start Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->activities as $activity)
                                        <tr>
                                            <td>{{ $activity->subject }}</td>
                                            <td>{{ ucfirst($activity->type) }}</td>
                                            <td>{{ $activity->start_date ? $activity->start_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>{{ ucfirst($activity->status) }}</td>
                                            <td>
                                                <a href="{{ route('activities.show', $activity) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No activities found.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Contacts -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Contacts</h5>
                    <a href="{{ route('contacts.create', ['customer_id' => $customer->id]) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg"></i> Add Contact
                    </a>
                </div>
                <div class="card-body">
                    @if($customer->contacts->count() > 0)
                        @foreach($customer->contacts as $contact)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $contact->first_name }} {{ $contact->last_name }}</h6>
                                    <small class="text-muted">{{ $contact->position }}</small>
                                </div>
                                <a href="{{ route('contacts.show', $contact) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">No contacts found.</p>
                    @endif
                </div>
            </div>

            <!-- Notes section disabled: no notes relation defined for Customer -->
        </div>
    </div>
    
@endsection 