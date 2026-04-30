package com.medicallab.reports.presentation.viewmodel

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.medicallab.reports.data.models.User
import com.medicallab.reports.data.repository.AuthRepository
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.launch

data class AuthUiState(
    val isLoading: Boolean = false,
    val user: User? = null,
    val error: String? = null,
    val isLoggedIn: Boolean = false
)

class AuthViewModel(
    private val authRepository: AuthRepository
) : ViewModel() {

    private val _uiState = MutableStateFlow<AuthUiState>(AuthUiState())
    val uiState = _uiState.asStateFlow()

    fun login(username: String, password: String) {
        viewModelScope.launch {
            _uiState.value = _uiState.value.copy(isLoading = true, error = null)
            val result = authRepository.login(username, password)
            result.onSuccess { user ->
                _uiState.value = AuthUiState(
                    isLoading = false,
                    user = user,
                    isLoggedIn = true
                )
            }
            result.onFailure { exception ->
                _uiState.value = AuthUiState(
                    isLoading = false,
                    error = exception.message ?: "Login failed"
                )
            }
        }
    }

    fun logout() {
        viewModelScope.launch {
            _uiState.value = _uiState.value.copy(isLoading = true)
            authRepository.logout()
            _uiState.value = AuthUiState()
        }
    }

    fun changePassword(userId: Int, oldPassword: String, newPassword: String) {
        viewModelScope.launch {
            _uiState.value = _uiState.value.copy(isLoading = true, error = null)
            val result = authRepository.changePassword(userId, oldPassword, newPassword)
            result.onSuccess {
                _uiState.value = _uiState.value.copy(
                    isLoading = false,
                    error = "Password changed successfully"
                )
            }
            result.onFailure { exception ->
                _uiState.value = _uiState.value.copy(
                    isLoading = false,
                    error = exception.message ?: "Password change failed"
                )
            }
        }
    }
}
