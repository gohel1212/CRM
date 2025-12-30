import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../models/customer.dart';
import '../services/api_service.dart';

class CustomerScreen extends StatefulWidget {
  const CustomerScreen({super.key});

  @override
  State<CustomerScreen> createState() => _CustomerScreenState();
}

class _CustomerScreenState extends State<CustomerScreen> {
  List<Customer> _customers = [];
  bool _isLoading = true;
  String _selectedStatus = 'all';

  @override
  void initState() {
    super.initState();
    _loadCustomers();
  }

  Future<void> _loadCustomers() async {
    setState(() {
      _isLoading = true;
    });

    try {
      final customers = await ApiService.getCustomers();
      setState(() {
        _customers = customers;
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _isLoading = false;
      });
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Error loading customers: $e'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  List<Customer> get _filteredCustomers {
    if (_selectedStatus == 'all') {
      return _customers;
    }
    return _customers.where((customer) => customer.status == _selectedStatus).toList();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[100],
      appBar: AppBar(
        title: const Text('Customers'),
        backgroundColor: Theme.of(context).primaryColor,
        foregroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () => context.go('/dashboard'),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _loadCustomers,
          ),
        ],
      ),
      body: Column(
        children: [
          // Filter Section
          Container(
            padding: const EdgeInsets.all(16),
            color: Colors.white,
            child: Row(
              children: [
                const Text('Filter by status: '),
                const SizedBox(width: 8),
                Expanded(
                  child: DropdownButton<String>(
                    value: _selectedStatus,
                    isExpanded: true,
                    items: const [
                      DropdownMenuItem(value: 'all', child: Text('All')),
                      DropdownMenuItem(value: 'active', child: Text('Active')),
                      DropdownMenuItem(value: 'inactive', child: Text('Inactive')),
                      DropdownMenuItem(value: 'potential', child: Text('Potential')),
                    ],
                    onChanged: (value) {
                      setState(() {
                        _selectedStatus = value!;
                      });
                    },
                  ),
                ),
              ],
            ),
          ),
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _filteredCustomers.isEmpty
                    ? _buildEmptyState()
                    : RefreshIndicator(
                        onRefresh: _loadCustomers,
                        child: ListView.builder(
                          padding: const EdgeInsets.all(16),
                          itemCount: _filteredCustomers.length,
                          itemBuilder: (context, index) {
                            final customer = _filteredCustomers[index];
                            return _buildCustomerCard(customer);
                          },
                        ),
                      ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () => _showAddCustomerDialog(),
        backgroundColor: Theme.of(context).primaryColor,
        child: const Icon(Icons.add, color: Colors.white),
      ),
    );
  }

  Widget _buildEmptyState() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            Icons.people,
            size: 80,
            color: Colors.grey[400],
          ),
          const SizedBox(height: 16),
          Text(
            'No customers yet',
            style: TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: Colors.grey[600],
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Add your first customer to get started',
            style: TextStyle(
              fontSize: 16,
              color: Colors.grey[500],
            ),
          ),
          const SizedBox(height: 24),
          ElevatedButton.icon(
            onPressed: () => _showAddCustomerDialog(),
            icon: const Icon(Icons.add),
            label: const Text('Add Customer'),
            style: ElevatedButton.styleFrom(
              backgroundColor: Theme.of(context).primaryColor,
              foregroundColor: Colors.white,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCustomerCard(Customer customer) {
    Color statusColor;
    IconData statusIcon;
    
    switch (customer.status) {
      case 'active':
        statusColor = Colors.green;
        statusIcon = Icons.check_circle;
        break;
      case 'inactive':
        statusColor = Colors.red;
        statusIcon = Icons.cancel;
        break;
      case 'potential':
      default:
        statusColor = Colors.orange;
        statusIcon = Icons.pending;
        break;
    }

    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      elevation: 2,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
      ),
      child: ListTile(
        contentPadding: const EdgeInsets.all(16),
        leading: CircleAvatar(
          backgroundColor: Theme.of(context).primaryColor,
          child: Text(
            customer.name.isNotEmpty ? customer.name[0].toUpperCase() : '?',
            style: const TextStyle(
              color: Colors.white,
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
        title: Text(
          customer.name,
          style: const TextStyle(
            fontWeight: FontWeight.bold,
            fontSize: 16,
          ),
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (customer.email != null && customer.email!.isNotEmpty)
              Padding(
                padding: const EdgeInsets.only(top: 4),
                child: Row(
                  children: [
                    const Icon(Icons.email, size: 16, color: Colors.grey),
                    const SizedBox(width: 4),
                    Expanded(
                      child: Text(
                        customer.email!,
                        style: const TextStyle(fontSize: 14),
                      ),
                    ),
                  ],
                ),
              ),
            if (customer.phone != null && customer.phone!.isNotEmpty)
              Padding(
                padding: const EdgeInsets.only(top: 4),
                child: Row(
                  children: [
                    const Icon(Icons.phone, size: 16, color: Colors.grey),
                    const SizedBox(width: 4),
                    Expanded(
                      child: Text(
                        customer.phone!,
                        style: const TextStyle(fontSize: 14),
                      ),
                    ),
                  ],
                ),
              ),
            if (customer.company != null && customer.company!.isNotEmpty)
              Padding(
                padding: const EdgeInsets.only(top: 4),
                child: Row(
                  children: [
                    const Icon(Icons.business, size: 16, color: Colors.grey),
                    const SizedBox(width: 4),
                    Expanded(
                      child: Text(
                        customer.company!,
                        style: const TextStyle(fontSize: 14),
                      ),
                    ),
                  ],
                ),
              ),
            const SizedBox(height: 8),
            Row(
              children: [
                Icon(statusIcon, size: 16, color: statusColor),
                const SizedBox(width: 4),
                Text(
                  customer.status.toUpperCase(),
                  style: TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.bold,
                    color: statusColor,
                  ),
                ),
              ],
            ),
          ],
        ),
        trailing: PopupMenuButton<String>(
          onSelected: (value) {
            if (value == 'edit') {
              _showEditCustomerDialog(customer);
            } else if (value == 'delete') {
              _showDeleteConfirmation(customer);
            }
          },
          itemBuilder: (context) => [
            const PopupMenuItem(
              value: 'edit',
              child: Row(
                children: [
                  Icon(Icons.edit),
                  SizedBox(width: 8),
                  Text('Edit'),
                ],
              ),
            ),
            const PopupMenuItem(
              value: 'delete',
              child: Row(
                children: [
                  Icon(Icons.delete, color: Colors.red),
                  SizedBox(width: 8),
                  Text('Delete', style: TextStyle(color: Colors.red)),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _showAddCustomerDialog() {
    _showCustomerDialog();
  }

  void _showEditCustomerDialog(Customer customer) {
    _showCustomerDialog(customer: customer);
  }

  void _showCustomerDialog({Customer? customer}) {
    final nameController = TextEditingController(text: customer?.name ?? '');
    final emailController = TextEditingController(text: customer?.email ?? '');
    final phoneController = TextEditingController(text: customer?.phone ?? '');
    final companyController = TextEditingController(text: customer?.company ?? '');
    final addressController = TextEditingController(text: customer?.address ?? '');
    String selectedStatus = customer?.status ?? 'potential';

    showDialog(
      context: context,
      builder: (context) => StatefulBuilder(
        builder: (context, setState) => AlertDialog(
          title: Text(customer == null ? 'Add Customer' : 'Edit Customer'),
          content: SingleChildScrollView(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                TextField(
                  controller: nameController,
                  decoration: const InputDecoration(
                    labelText: 'Name *',
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: emailController,
                  decoration: const InputDecoration(
                    labelText: 'Email',
                    border: OutlineInputBorder(),
                  ),
                  keyboardType: TextInputType.emailAddress,
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: phoneController,
                  decoration: const InputDecoration(
                    labelText: 'Phone',
                    border: OutlineInputBorder(),
                  ),
                  keyboardType: TextInputType.phone,
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: companyController,
                  decoration: const InputDecoration(
                    labelText: 'Company',
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: addressController,
                  decoration: const InputDecoration(
                    labelText: 'Address',
                    border: OutlineInputBorder(),
                  ),
                  maxLines: 2,
                ),
                const SizedBox(height: 16),
                DropdownButtonFormField<String>(
                  initialValue: selectedStatus,
                  decoration: const InputDecoration(
                    labelText: 'Status',
                    border: OutlineInputBorder(),
                  ),
                  items: const [
                    DropdownMenuItem(value: 'active', child: Text('Active')),
                    DropdownMenuItem(value: 'inactive', child: Text('Inactive')),
                    DropdownMenuItem(value: 'potential', child: Text('Potential')),
                  ],
                  onChanged: (value) {
                    setState(() {
                      selectedStatus = value!;
                    });
                  },
                ),
              ],
            ),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: const Text('Cancel'),
            ),
            ElevatedButton(
            onPressed: () async {
              final navigator = Navigator.of(context);
              final messenger = ScaffoldMessenger.of(context);
              
              if (nameController.text.trim().isEmpty) {
                messenger.showSnackBar(
                  const SnackBar(
                    content: Text('Name is required'),
                    backgroundColor: Colors.red,
                  ),
                );
                return;
              }

              final newCustomer = Customer(
                id: customer?.id,
                name: nameController.text.trim(),
                email: emailController.text.trim().isEmpty ? null : emailController.text.trim(),
                phone: phoneController.text.trim().isEmpty ? null : phoneController.text.trim(),
                company: companyController.text.trim().isEmpty ? null : companyController.text.trim(),
                address: addressController.text.trim().isEmpty ? null : addressController.text.trim(),
                status: selectedStatus,
              );

              bool success;
              if (customer == null) {
                success = await ApiService.createCustomer(newCustomer);
              } else {
                success = await ApiService.updateCustomer(newCustomer);
              }

              if (success) {
                if (mounted) {
                  navigator.pop();
                  _loadCustomers();
                  messenger.showSnackBar(
                    SnackBar(
                      content: Text(customer == null ? 'Customer added successfully' : 'Customer updated successfully'),
                      backgroundColor: Colors.green,
                    ),
                  );
                }
              } else {
                if (mounted) {
                  messenger.showSnackBar(
                    const SnackBar(
                      content: Text('Failed to save customer'),
                      backgroundColor: Colors.red,
                    ),
                  );
                }
              }
            },
              child: Text(customer == null ? 'Add' : 'Update'),
            ),
          ],
        ),
      ),
    );
  }

  void _showDeleteConfirmation(Customer customer) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Delete Customer'),
        content: Text('Are you sure you want to delete ${customer.name}?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(context).pop(),
            child: const Text('Cancel'),
          ),
          ElevatedButton(
            onPressed: () async {
              final navigator = Navigator.of(context);
              final messenger = ScaffoldMessenger.of(context);
              navigator.pop();
              final success = await ApiService.deleteCustomer(customer.id!);
              if (mounted) {
                if (success) {
                  _loadCustomers();
                  messenger.showSnackBar(
                    const SnackBar(
                      content: Text('Customer deleted successfully'),
                      backgroundColor: Colors.green,
                    ),
                  );
                } else {
                  messenger.showSnackBar(
                    const SnackBar(
                      content: Text('Failed to delete customer'),
                      backgroundColor: Colors.red,
                    ),
                  );
                }
              }
            },
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
            child: const Text('Delete', style: TextStyle(color: Colors.white)),
          ),
        ],
      ),
    );
  }
}
