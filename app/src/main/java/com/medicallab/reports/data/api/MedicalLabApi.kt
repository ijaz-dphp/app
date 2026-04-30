package com.medicallab.reports.data.api

import com.medicallab.reports.data.models.*
import retrofit2.http.*

data class LoginRequest(
    val username: String,
    val password: String
)

data class LoginResponse(
    val success: Boolean,
    val message: String,
    val user: User? = null,
    val token: String? = null
)

data class ApiResponse<T>(
    val success: Boolean,
    val message: String,
    val data: T? = null
)

interface MedicalLabApi {

    // Auth endpoints
    @POST("login_process.php")
    suspend fun login(@Body request: LoginRequest): LoginResponse

    @POST("logout.php")
    suspend fun logout(): ApiResponse<Unit>

    @POST("change_password_process.php")
    suspend fun changePassword(
        @Query("user_id") userId: Int,
        @Query("old_password") oldPassword: String,
        @Query("new_password") newPassword: String
    ): ApiResponse<Unit>

    // Patient endpoints
    @GET("search_patient.php")
    suspend fun searchPatient(
        @Query("mrn") mrn: String? = null,
        @Query("name") name: String? = null
    ): ApiResponse<List<Patient>>

    @GET("get_patient_by_mrn.php")
    suspend fun getPatientByMrn(@Query("mrn") mrn: String): ApiResponse<Patient>

    // Reports endpoints
    @GET("reports_list.php")
    suspend fun getReports(
        @Query("patient_id") patientId: Int? = null,
        @Query("mrn") mrn: String? = null,
        @Query("limit") limit: Int = 50,
        @Query("offset") offset: Int = 0
    ): ApiResponse<List<Report>>

    @GET("view_report.php")
    suspend fun getReport(@Query("id") reportId: Int): ApiResponse<Map<String, Any>>

    @GET("get_test_parameters.php")
    suspend fun getReportResults(
        @Query("report_id") reportId: Int
    ): ApiResponse<List<ReportResult>>

    // Test categories and parameters
    @GET("manage_test_hierarchy.php")
    suspend fun getTestCategories(): ApiResponse<List<TestCategory>>

    @GET("get_test_parameters.php")
    suspend fun getTestParameters(
        @Query("category_id") categoryId: Int
    ): ApiResponse<List<TestParameter>>

    // PDF Download
    @GET("generate_pdf.php")
    suspend fun downloadReportPdf(
        @Query("id") reportId: Int,
        @Query("action") action: String = "download"
    ): String

    // Admin endpoints
    @GET("ajax_manage_tests.php")
    suspend fun getTests(
        @Query("action") action: String = "list"
    ): ApiResponse<List<Map<String, Any>>>

    @POST("save_user.php")
    suspend fun saveUser(
        @Query("username") username: String,
        @Query("email") email: String,
        @Query("password") password: String,
        @Query("role") role: String
    ): ApiResponse<User>

    @GET("manage_users.php")
    suspend fun getUsers(): ApiResponse<List<User>>

    @POST("update_test.php")
    suspend fun updateTest(
        @Query("id") testId: Int,
        @Query("test_name") testName: String,
        @Query("min_value") minValue: String? = null,
        @Query("max_value") maxValue: String? = null,
        @Query("unit") unit: String? = null
    ): ApiResponse<TestParameter>
}
