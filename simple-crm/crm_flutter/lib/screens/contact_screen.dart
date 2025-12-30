import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../models/contact.dart';
import '../services/api_service.dart';

class ContactScreen extends StatefulWidget {
  const ContactScreen({super.key});

  @override
  State<ContactScreen> createState() => _ContactScreenState();
}

class _ContactScreenState extends State<ContactScreen> {
  List<Contact> _contacts = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadContacts();
  }

  Future<void> _loadContacts() async {
    setState(() {
      _isLoading = true;
    });

    try {
      final contacts = await ApiService.getContacts();
      setState(() {
        _contacts = contacts;
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _isLoading = false;
      });
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Error loading contacts: $e'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[100],
      appBar: AppBar(
        title: const Text('Contacts'),
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
            onPressed: _loadContacts,
          ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _contacts.isEmpty
              ? _buildEmptyState()
              : RefreshIndicator(
                  onRefresh: _loadContacts,
                  child: ListView.builder(
                    padding: const EdgeInsets.all(16),
                    itemCount: _contacts.length,
                    itemBuilder: (context, index) {
                      final contact = _contacts[index];
                      return _buildContactCard(contact);
                    },
                  ),
                ),
      floatingActionButton: FloatingActionButton(
        onPressed: () => _showAddContactDialog(),
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
            Icons.contacts,
            size: 80,
            color: Colors.grey[400],
          ),
          const SizedBox(height: 16),
          Text(
            'No contacts yet',
            style: TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: Colors.grey[600],
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Add your first contact to get started',
            style: TextStyle(
              fontSize: 16,
              color: Colors.grey[500],
            ),
          ),
          const SizedBox(height: 24),
          ElevatedButton.icon(
            onPressed: () => _showAddContactDialog(),
            icon: const Icon(Icons.add),
            label: const Text('Add Contact'),
            style: ElevatedButton.styleFrom(
              backgroundColor: Theme.of(context).primaryColor,
              foregroundColor: Colors.white,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildContactCard(Contact contact) {
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
            contact.name.isNotEmpty ? contact.name[0].toUpperCase() : '?',
            style: const TextStyle(
              color: Colors.white,
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
        title: Text(
          contact.name,
          style: const TextStyle(
            fontWeight: FontWeight.bold,
            fontSize: 16,
          ),
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (contact.email != null && contact.email!.isNotEmpty)
              Padding(
                padding: const EdgeInsets.only(top: 4),
                child: Row(
                  children: [
                    const Icon(Icons.email, size: 16, color: Colors.grey),
                    const SizedBox(width: 4),
                    Expanded(
                      child: Text(
                        contact.email!,
                        style: const TextStyle(fontSize: 14),
                      ),
                    ),
                  ],
                ),
              ),
            if (contact.phone != null && contact.phone!.isNotEmpty)
              Padding(
                padding: const EdgeInsets.only(top: 4),
                child: Row(
                  children: [
                    const Icon(Icons.phone, size: 16, color: Colors.grey),
                    const SizedBox(width: 4),
                    Expanded(
                      child: Text(
                        contact.phone!,
                        style: const TextStyle(fontSize: 14),
                      ),
                    ),
                  ],
                ),
              ),
            if (contact.company != null && contact.company!.isNotEmpty)
              Padding(
                padding: const EdgeInsets.only(top: 4),
                child: Row(
                  children: [
                    const Icon(Icons.business, size: 16, color: Colors.grey),
                    const SizedBox(width: 4),
                    Expanded(
                      child: Text(
                        contact.company!,
                        style: const TextStyle(fontSize: 14),
                      ),
                    ),
                  ],
                ),
              ),
          ],
        ),
        trailing: PopupMenuButton<String>(
          onSelected: (value) {
            if (value == 'edit') {
              _showEditContactDialog(contact);
            } else if (value == 'delete') {
              _showDeleteConfirmation(contact);
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

  void _showAddContactDialog() {
    _showContactDialog();
  }

  void _showEditContactDialog(Contact contact) {
    _showContactDialog(contact: contact);
  }

  void _showContactDialog({Contact? contact}) {
    final nameController = TextEditingController(text: contact?.name ?? '');
    final emailController = TextEditingController(text: contact?.email ?? '');
    final phoneController = TextEditingController(text: contact?.phone ?? '');
    final companyController = TextEditingController(text: contact?.company ?? '');
    final positionController = TextEditingController(text: contact?.position ?? '');
    final notesController = TextEditingController(text: contact?.notes ?? '');

    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text(contact == null ? 'Add Contact' : 'Edit Contact'),
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
                controller: positionController,
                decoration: const InputDecoration(
                  labelText: 'Position',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 16),
              TextField(
                controller: notesController,
                decoration: const InputDecoration(
                  labelText: 'Notes',
                  border: OutlineInputBorder(),
                ),
                maxLines: 3,
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

              final newContact = Contact(
                id: contact?.id,
                name: nameController.text.trim(),
                email: emailController.text.trim().isEmpty ? null : emailController.text.trim(),
                phone: phoneController.text.trim().isEmpty ? null : phoneController.text.trim(),
                company: companyController.text.trim().isEmpty ? null : companyController.text.trim(),
                position: positionController.text.trim().isEmpty ? null : positionController.text.trim(),
                notes: notesController.text.trim().isEmpty ? null : notesController.text.trim(),
              );

              bool success;
              if (contact == null) {
                success = await ApiService.createContact(newContact);
              } else {
                success = await ApiService.updateContact(newContact);
              }

              if (success) {
                if (mounted) {
                  navigator.pop();
                  _loadContacts();
                  messenger.showSnackBar(
                    SnackBar(
                      content: Text(contact == null ? 'Contact added successfully' : 'Contact updated successfully'),
                      backgroundColor: Colors.green,
                    ),
                  );
                }
              } else {
                if (mounted) {
                  messenger.showSnackBar(
                    const SnackBar(
                      content: Text('Failed to save contact'),
                      backgroundColor: Colors.red,
                    ),
                  );
                }
              }
            },
            child: Text(contact == null ? 'Add' : 'Update'),
          ),
        ],
      ),
    );
  }

  void _showDeleteConfirmation(Contact contact) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Delete Contact'),
        content: Text('Are you sure you want to delete ${contact.name}?'),
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
              final success = await ApiService.deleteContact(contact.id!);
              if (mounted) {
                if (success) {
                  _loadContacts();
                  messenger.showSnackBar(
                    const SnackBar(
                      content: Text('Contact deleted successfully'),
                      backgroundColor: Colors.green,
                    ),
                  );
                } else {
                  messenger.showSnackBar(
                    const SnackBar(
                      content: Text('Failed to delete contact'),
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
