import 'package:flutter/foundation.dart';
import '../models/user.dart';
import '../services/api_service.dart';

class AuthProvider with ChangeNotifier {
  User? _user;
  bool _isLoading = false;
  String? _error;

  User? get user => _user;
  bool get isLoading => _isLoading;
  String? get error => _error;
  bool get isAuthenticated => _user != null;

  void _setLoading(bool loading) {
    _isLoading = loading;
    notifyListeners();
  }

  void _setError(String? error) {
    _error = error;
    notifyListeners();
  }

  Future<bool> login(String email, String password) async {
    _setLoading(true);
    _setError(null);

    try {
      final result = await ApiService.login(email, password);
      
      if (result['success']) {
        _user = result['user'];
        _setLoading(false);
        return true;
      } else {
        _setError(result['message']);
        _setLoading(false);
        return false;
      }
    } catch (e) {
      _setError('An unexpected error occurred');
      _setLoading(false);
      return false;
    }
  }

  Future<bool> register(String name, String email, String password) async {
    _setLoading(true);
    _setError(null);

    try {
      final result = await ApiService.register(name, email, password);
      
      if (result['success']) {
        _setLoading(false);
        return true;
      } else {
        _setError(result['message']);
        _setLoading(false);
        return false;
      }
    } catch (e) {
      _setError('An unexpected error occurred');
      _setLoading(false);
      return false;
    }
  }

  Future<void> logout() async {
    await ApiService.logout();
    _user = null;
    _error = null;
    notifyListeners();
  }

  void clearError() {
    _setError(null);
  }
}
