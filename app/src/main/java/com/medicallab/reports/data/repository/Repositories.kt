package com.medicallab.reports.data.repository

import com.medicallab.reports.data.api.LoginRequest
import com.medicallab.reports.data.api.MedicalLabApi
import com.medicallab.reports.data.models.*
import com.medicallab.reports.data.preferences.PreferencesManager

class AuthRepository(
    private val api: MedicalLabApi,
    private val preferencesManager: PreferencesManager
) {
    suspend fun login(username: String, password: String): Result<User> = try {
        val response = api.login(LoginRequest(username, password))
        if (response.success && response.user != null) {
            response.token?.let {
                preferencesManager.saveToken(it)
            }
            response.user.let {
                preferencesManager.saveUser(it.id, it.username, it.email, it.role)
            }
            Result.success(response.user)
        } else {
            Result.failure(Exception(response.message))
        }
    } catch (e: Exception) {
        Result.failure(e)
    }

    suspend fun logout(): Result<Unit> = try {
        api.logout()
        preferencesManager.logout()
        Result.success(Unit)
    } catch (e: Exception) {
        Result.failure(e)
    }

    suspend fun changePassword(userId: Int, oldPassword: String, newPassword: String): Result<Unit> =
        try {
            val response = api.changePassword(userId, oldPassword, newPassword)
            if (response.success) {
                Result.success(Unit)
            } else {
                Result.failure(Exception(response.message))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
}

class PatientRepository(
    private val api: MedicalLabApi
) {
    suspend fun searchPatient(mrn: String? = null, name: String? = null): Result<List<Patient>> = try {
        val response = api.searchPatient(mrn, name)
        if (response.success && response.data != null) {
            Result.success(response.data)
        } else {
            Result.failure(Exception(response.message))
        }
    } catch (e: Exception) {
        Result.failure(e)
    }

    suspend fun getPatientByMrn(mrn: String): Result<Patient> = try {
        val response = api.getPatientByMrn(mrn)
        if (response.success && response.data != null) {
            Result.success(response.data)
        } else {
            Result.failure(Exception(response.message))
        }
    } catch (e: Exception) {
        Result.failure(e)
    }
}

class ReportRepository(
    private val api: MedicalLabApi
) {
    suspend fun getReports(
        patientId: Int? = null,
        mrn: String? = null,
        limit: Int = 50,
        offset: Int = 0
    ): Result<List<Report>> = try {
        val response = api.getReports(patientId, mrn, limit, offset)
        if (response.success && response.data != null) {
            Result.success(response.data)
        } else {
            Result.failure(Exception(response.message))
        }
    } catch (e: Exception) {
        Result.failure(e)
    }

    suspend fun getReport(reportId: Int): Result<Map<String, Any>> = try {
        val response = api.getReport(reportId)
        if (response.success && response.data != null) {
            Result.success(response.data)
        } else {
            Result.failure(Exception(response.message))
        }
    } catch (e: Exception) {
        Result.failure(e)
    }

    suspend fun getReportResults(reportId: Int): Result<List<ReportResult>> = try {
        val response = api.getReportResults(reportId)
        if (response.success && response.data != null) {
            Result.success(response.data)
        } else {
            Result.failure(Exception(response.message))
        }
    } catch (e: Exception) {
        Result.failure(e)
    }

    suspend fun downloadReportPdf(reportId: Int): Result<String> = try {
        val html = api.downloadReportPdf(reportId)
        Result.success(html)
    } catch (e: Exception) {
        Result.failure(e)
    }
}

class TestRepository(
    private val api: MedicalLabApi
) {
    suspend fun getTestCategories(): Result<List<TestCategory>> = try {
        val response = api.getTestCategories()
        if (response.success && response.data != null) {
            Result.success(response.data)
        } else {
            Result.failure(Exception(response.message))
        }
    } catch (e: Exception) {
        Result.failure(e)
    }

    suspend fun getTestParameters(categoryId: Int): Result<List<TestParameter>> = try {
        val response = api.getTestParameters(categoryId)
        if (response.success && response.data != null) {
            Result.success(response.data)
        } else {
            Result.failure(Exception(response.message))
        }
    } catch (e: Exception) {
        Result.failure(e)
    }

    suspend fun getTests(): Result<List<Map<String, Any>>> = try {
        val response = api.getTests()
        if (response.success && response.data != null) {
            Result.success(response.data)
        } else {
            Result.failure(Exception(response.message))
        }
    } catch (e: Exception) {
        Result.failure(e)
    }
}

class AdminRepository(
    private val api: MedicalLabApi
) {
    suspend fun getUsers(): Result<List<User>> = try {
        val response = api.getUsers()
        if (response.success && response.data != null) {
            Result.success(response.data)
        } else {
            Result.failure(Exception(response.message))
        }
    } catch (e: Exception) {
        Result.failure(e)
    }

    suspend fun saveUser(username: String, email: String, password: String, role: String): Result<User> =
        try {
            val response = api.saveUser(username, email, password, role)
            if (response.success && response.data != null) {
                Result.success(response.data)
            } else {
                Result.failure(Exception(response.message))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }

    suspend fun updateTest(
        testId: Int,
        testName: String,
        minValue: String? = null,
        maxValue: String? = null,
        unit: String? = null
    ): Result<TestParameter> = try {
        val response = api.updateTest(testId, testName, minValue, maxValue, unit)
        if (response.success && response.data != null) {
            Result.success(response.data)
        } else {
            Result.failure(Exception(response.message))
        }
    } catch (e: Exception) {
        Result.failure(e)
    }
}
