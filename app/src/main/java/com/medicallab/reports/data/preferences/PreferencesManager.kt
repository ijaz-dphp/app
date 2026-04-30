package com.medicallab.reports.data.preferences

import android.content.Context
import androidx.datastore.preferences.core.booleanPreferencesKey
import androidx.datastore.preferences.core.edit
import androidx.datastore.preferences.core.intPreferencesKey
import androidx.datastore.preferences.core.stringPreferencesKey
import androidx.datastore.preferences.preferencesDataStore
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.map

private val Context.dataStore by preferencesDataStore(name = "app_prefs")

class PreferencesManager(private val context: Context) {

    companion object {
        private val TOKEN = stringPreferencesKey("token")
        private val USER_ID = intPreferencesKey("user_id")
        private val USERNAME = stringPreferencesKey("username")
        private val USER_ROLE = stringPreferencesKey("user_role")
        private val USER_EMAIL = stringPreferencesKey("user_email")
        private val IS_LOGGED_IN = booleanPreferencesKey("is_logged_in")
        private val DARK_MODE = booleanPreferencesKey("dark_mode")
        private val BASE_URL = stringPreferencesKey("base_url")
    }

    // Token management
    fun getToken(): String? {
        return try {
            context.dataStore.data.map { it[TOKEN] }
            null
        } catch (e: Exception) {
            null
        }
    }

    suspend fun saveToken(token: String) {
        context.dataStore.edit { preferences ->
            preferences[TOKEN] = token
        }
    }

    suspend fun clearToken() {
        context.dataStore.edit { preferences ->
            preferences.remove(TOKEN)
        }
    }

    // User management
    suspend fun saveUser(userId: Int, username: String, email: String, role: String) {
        context.dataStore.edit { preferences ->
            preferences[USER_ID] = userId
            preferences[USERNAME] = username
            preferences[USER_EMAIL] = email
            preferences[USER_ROLE] = role
            preferences[IS_LOGGED_IN] = true
        }
    }

    fun getUserId(): Flow<Int?> {
        return context.dataStore.data.map { it[USER_ID] }
    }

    fun getUsername(): Flow<String?> {
        return context.dataStore.data.map { it[USERNAME] }
    }

    fun getUserEmail(): Flow<String?> {
        return context.dataStore.data.map { it[USER_EMAIL] }
    }

    fun getUserRole(): Flow<String?> {
        return context.dataStore.data.map { it[USER_ROLE] }
    }

    fun isLoggedIn(): Flow<Boolean> {
        return context.dataStore.data.map { it[IS_LOGGED_IN] ?: false }
    }

    suspend fun logout() {
        context.dataStore.edit { preferences ->
            preferences.clear()
        }
    }

    // Settings
    fun getDarkMode(): Flow<Boolean> {
        return context.dataStore.data.map { it[DARK_MODE] ?: false }
    }

    suspend fun setDarkMode(enabled: Boolean) {
        context.dataStore.edit { preferences ->
            preferences[DARK_MODE] = enabled
        }
    }

    // Base URL configuration
    fun getBaseUrl(): Flow<String?> {
        return context.dataStore.data.map { it[BASE_URL] }
    }

    suspend fun setBaseUrl(url: String) {
        context.dataStore.edit { preferences ->
            preferences[BASE_URL] = url
        }
    }
}
