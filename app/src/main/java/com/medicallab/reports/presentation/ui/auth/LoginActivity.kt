package com.medicallab.reports.presentation.ui.auth

import android.content.Intent
import android.os.Bundle
import android.widget.Toast
import androidx.activity.viewModels
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import com.medicallab.reports.databinding.ActivityLoginBinding
import com.medicallab.reports.data.api.NetworkClient
import com.medicallab.reports.data.preferences.PreferencesManager
import com.medicallab.reports.data.repository.AuthRepository
import com.medicallab.reports.presentation.ui.dashboard.DashboardActivity
import com.medicallab.reports.presentation.viewmodel.AuthViewModel
import kotlinx.coroutines.launch
import timber.log.Timber

class LoginActivity : AppCompatActivity() {

    private lateinit var binding: ActivityLoginBinding
    private lateinit var authViewModel: AuthViewModel

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityLoginBinding.inflate(layoutInflater)
        setContentView(binding.root)

        // Initialize ViewModel with repositories
        val api = NetworkClient.getApiService(this)
        val preferencesManager = PreferencesManager(this)
        val repository = AuthRepository(api, preferencesManager)
        authViewModel = AuthViewModel(repository)

        setupViews()
        observeUiState()
    }

    private fun setupViews() {
        binding.loginButton.setOnClickListener {
            val username = binding.usernameInput.text.toString().trim()
            val password = binding.passwordInput.text.toString()

            if (validateInputs(username, password)) {
                authViewModel.login(username, password)
            }
        }

        binding.configButton.setOnClickListener {
            showConfigDialog()
        }
    }

    private fun validateInputs(username: String, password: String): Boolean {
        when {
            username.isEmpty() -> {
                binding.usernameInput.error = "Username is required"
                return false
            }
            password.isEmpty() -> {
                binding.passwordInput.error = "Password is required"
                return false
            }
            else -> return true
        }
    }

    private fun observeUiState() {
        lifecycleScope.launch {
            authViewModel.uiState.collect { state ->
                binding.loginButton.isEnabled = !state.isLoading
                binding.usernameInput.isEnabled = !state.isLoading
                binding.passwordInput.isEnabled = !state.isLoading

                when {
                    state.isLoading -> {
                        binding.loginButton.text = "Logging in..."
                    }
                    state.isLoggedIn && state.user != null -> {
                        Timber.d("Login successful for user: ${state.user.username}")
                        navigateToDashboard(state.user.role)
                    }
                    state.error != null -> {
                        binding.loginButton.text = "Login"
                        Toast.makeText(this@LoginActivity, state.error, Toast.LENGTH_SHORT).show()
                        Timber.e("Login failed: ${state.error}")
                    }
                }
            }
        }
    }

    private fun navigateToDashboard(userRole: String) {
        val intent = Intent(this, DashboardActivity::class.java).apply {
            putExtra("user_role", userRole)
            flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
        }
        startActivity(intent)
    }

    private fun showConfigDialog() {
        // TODO: Implement server configuration dialog
        Toast.makeText(this, "Configuration not implemented yet", Toast.LENGTH_SHORT).show()
    }
}
