<div class="space-y-6" x-data="dealForm()">
    <!-- Basic Information -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Basic Information</h3>
            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                    <div class="mt-1">
                        <input type="text" name="title" id="title" 
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md"
                               value="{{ old('title', $deal->title ?? '') }}" required>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="pipeline_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pipeline</label>
                    <div class="mt-1">
                        <select id="pipeline_id" name="pipeline_id" x-model="selectedPipelineId"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                            @foreach($pipelines as $pipeline)
                                <option value="{{ $pipeline->id }}" 
                                    {{ old('pipeline_id', $deal->pipeline_id ?? '') == $pipeline->id ? 'selected' : '' }}>
                                    {{ $pipeline->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="pipeline_stage_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stage</label>
                    <div class="mt-1">
                        <select id="pipeline_stage_id" name="pipeline_stage_id"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                            <template x-for="stage in stages" :key="stage.id">
                                <option :value="stage.id" 
                                        :selected="stage.id == '{{ old('pipeline_stage_id', $deal->pipeline_stage_id ?? '') }}'">
                                    <span x-text="stage.name"></span>
                                </option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="organization_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Organization</label>
                    <div class="mt-1">
                        <select id="organization_id" name="organization_id"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                            <option value="">Select Organization</option>
                            @foreach($organizations as $organization)
                                <option value="{{ $organization->id }}"
                                    {{ old('organization_id', $deal->organization_id ?? '') == $organization->id ? 'selected' : '' }}>
                                    {{ $organization->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="contact_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact</label>
                    <div class="mt-1">
                        <select id="contact_id" name="contact_id"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                            <option value="">Select Contact</option>
                            @foreach($contacts as $contact)
                                <option value="{{ $contact->id }}"
                                    {{ old('contact_id', $deal->contact_id ?? '') == $contact->id ? 'selected' : '' }}>
                                    {{ $contact->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Value</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="value" id="value" step="0.01" min="0"
                               class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md"
                               value="{{ old('value', $deal->value ?? '0.00') }}" required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">USD</span>
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="expected_close_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expected Close Date</label>
                    <div class="mt-1">
                        <input type="date" name="expected_close_date" id="expected_close_date"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md"
                               value="{{ old('expected_close_date', $deal->expected_close_date?->format('Y-m-d') ?? '') }}" required>
                    </div>
                </div>

                <div class="sm:col-span-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <div class="mt-1">
                        <textarea id="description" name="description" rows="3"
                                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">{{ old('description', $deal->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Products</h3>
                <button type="button" @click="addProduct"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Product
                </button>
            </div>

            <div class="mt-6 space-y-4">
                <template x-for="(product, index) in products" :key="index">
                    <div class="flex items-center space-x-4">
                        <div class="flex-grow grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-12">
                            <input type="hidden" :name="'products['+index+'][id]'" x-model="product.id">
                            
                            <div class="sm:col-span-4">
                                <label :for="'products['+index+'][product_id]'" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product</label>
                                <select :name="'products['+index+'][product_id]'" x-model="product.product_id"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Product</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label :for="'products['+index+'][quantity]'" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                                <input type="number" :name="'products['+index+'][quantity]'" x-model="product.quantity" min="1"
                                       class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       @input="calculateTotal">
                            </div>

                            <div class="sm:col-span-2">
                                <label :for="'products['+index+'][price]'" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                                <input type="number" :name="'products['+index+'][price]'" x-model="product.price" step="0.01" min="0"
                                       class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       @input="calculateTotal">
                            </div>

                            <div class="sm:col-span-2">
                                <label :for="'products['+index+'][discount]'" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount</label>
                                <input type="number" :name="'products['+index+'][discount]'" x-model="product.discount" step="0.01" min="0"
                                       class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       @input="calculateTotal">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total</label>
                                <div class="mt-1 block w-full py-2 px-3 bg-gray-50 dark:bg-gray-600 rounded-md text-sm">
                                    <span x-text="formatCurrency(calculateProductTotal(product))"></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex-none">
                            <button type="button" @click="removeProduct(index)"
                                    class="inline-flex items-center p-2 border border-transparent rounded-full text-red-600 hover:bg-red-100 dark:hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-end text-base font-medium text-gray-900 dark:text-gray-100">
                        <span class="mr-4">Total Value:</span>
                        <span x-text="formatCurrency(total)"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Fields -->
    @if(isset($customFields) && $customFields->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Additional Information</h3>
                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    @foreach($customFields as $field)
                        <div class="sm:col-span-3">
                            <label for="custom_fields[{{ $field->id }}]" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $field->name }}
                                @if($field->is_required)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>
                            <div class="mt-1">
                                @switch($field->type)
                                    @case('text')
                                        <input type="text" 
                                               name="custom_fields[{{ $field->id }}]"
                                               value="{{ old('custom_fields.'.$field->id, $deal->customFields->where('custom_field_id', $field->id)->first()->value ?? '') }}"
                                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md"
                                               {{ $field->is_required ? 'required' : '' }}>
                                        @break
                                    @case('textarea')
                                        <textarea name="custom_fields[{{ $field->id }}]"
                                                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md"
                                                  {{ $field->is_required ? 'required' : '' }}>{{ old('custom_fields.'.$field->id, $deal->customFields->where('custom_field_id', $field->id)->first()->value ?? '') }}</textarea>
                                        @break
                                    @case('select')
                                        <select name="custom_fields[{{ $field->id }}]"
                                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md"
                                                {{ $field->is_required ? 'required' : '' }}>
                                            <option value="">Select {{ $field->name }}</option>
                                            @foreach($field->options as $option)
                                                <option value="{{ $option }}"
                                                    {{ old('custom_fields.'.$field->id, $deal->customFields->where('custom_field_id', $field->id)->first()->value ?? '') == $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @break
                                    @case('date')
                                        <input type="date"
                                               name="custom_fields[{{ $field->id }}]"
                                               value="{{ old('custom_fields.'.$field->id, $deal->customFields->where('custom_field_id', $field->id)->first()->value ?? '') }}"
                                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md"
                                               {{ $field->is_required ? 'required' : '' }}>
                                        @break
                                    @case('number')
                                        <input type="number"
                                               name="custom_fields[{{ $field->id }}]"
                                               value="{{ old('custom_fields.'.$field->id, $deal->customFields->where('custom_field_id', $field->id)->first()->value ?? '') }}"
                                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md"
                                               {{ $field->is_required ? 'required' : '' }}>
                                        @break
                                @endswitch
                            </div>
                            @if($field->description)
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $field->description }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function dealForm() {
    return {
        selectedPipelineId: '{{ old('pipeline_id', $deal->pipeline_id ?? $pipelines->first()->id) }}',
        stages: @json($pipelines->first()->stages),
        products: @json(old('products', $deal->products ?? [])),
        total: 0,

        init() {
            this.updateStages();
            this.calculateTotal();

            this.$watch('selectedPipelineId', () => {
                this.updateStages();
            });
        },

        updateStages() {
            const pipeline = @json($pipelines)->find(p => p.id == this.selectedPipelineId);
            this.stages = pipeline ? pipeline.stages : [];
        },

        addProduct() {
            this.products.push({
                id: null,
                product_id: '',
                quantity: 1,
                price: 0,
                discount: 0
            });
        },

        removeProduct(index) {
            this.products.splice(index, 1);
            this.calculateTotal();
        },

        calculateProductTotal(product) {
            return (product.quantity * product.price) - (product.discount || 0);
        },

        calculateTotal() {
            this.total = this.products.reduce((sum, product) => {
                return sum + this.calculateProductTotal(product);
            }, 0);
        },

        formatCurrency(value) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(value);
        }
    }
}
</script>
@endpush 