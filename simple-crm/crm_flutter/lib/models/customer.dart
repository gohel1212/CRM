class Customer {
  final int? id;
  final String name;
  final String? email;
  final String? phone;
  final String? company;
  final String? address;
  final String status;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  Customer({
    this.id,
    required this.name,
    this.email,
    this.phone,
    this.company,
    this.address,
    this.status = 'potential',
    this.createdAt,
    this.updatedAt,
  });

  factory Customer.fromJson(Map<String, dynamic> json) {
    return Customer(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      phone: json['phone'],
      company: json['company'],
      address: json['address'],
      status: json['status'] ?? 'potential',
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
      'address': address,
      'status': status,
    };
  }

  Customer copyWith({
    int? id,
    String? name,
    String? email,
    String? phone,
    String? company,
    String? address,
    String? status,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) {
    return Customer(
      id: id ?? this.id,
      name: name ?? this.name,
      email: email ?? this.email,
      phone: phone ?? this.phone,
      company: company ?? this.company,
      address: address ?? this.address,
      status: status ?? this.status,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }
}
