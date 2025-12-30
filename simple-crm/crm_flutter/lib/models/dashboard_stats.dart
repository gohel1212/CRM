class DashboardStats {
  final int totalContacts;
  final int totalCustomers;
  final int activeCustomers;
  final int potentialCustomers;

  DashboardStats({
    required this.totalContacts,
    required this.totalCustomers,
    required this.activeCustomers,
    required this.potentialCustomers,
  });

  factory DashboardStats.fromJson(Map<String, dynamic> json) {
    return DashboardStats(
      totalContacts: json['totalContacts'] ?? 0,
      totalCustomers: json['totalCustomers'] ?? 0,
      activeCustomers: json['activeCustomers'] ?? 0,
      potentialCustomers: json['potentialCustomers'] ?? 0,
    );
  }
}
