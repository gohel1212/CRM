class Contact {
  final int? id;
  final String name;
  final String? email;
  final String? phone;
  final String? company;
  final String? position;
  final String? notes;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  Contact({
    this.id,
    required this.name,
    this.email,
    this.phone,
    this.company,
    this.position,
    this.notes,
    this.createdAt,
    this.updatedAt,
  });

  factory Contact.fromJson(Map<String, dynamic> json) {
    return Contact(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      phone: json['phone'],
      company: json['company'],
      position: json['position'],
      notes: json['notes'],
      createdAt: json['created_at'] != null ? DateTime.parse(json['created_at']) : null,
      updatedAt: json['updated_at'] != null ? DateTime.parse(json['updated_at']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'phone': phone,
      'company': company,
      'position': position,
      'notes': notes,
    };
  }

  Contact copyWith({
    int? id,
    String? name,
    String? email,
    String? phone,
    String? company,
    String? position,
    String? notes,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) {
    return Contact(
      id: id ?? this.id,
      name: name ?? this.name,
      email: email ?? this.email,
      phone: phone ?? this.phone,
      company: company ?? this.company,
      position: position ?? this.position,
      notes: notes ?? this.notes,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }
}
