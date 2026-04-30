# Retrofit
-dontnwarn retrofit.**
-keep class retrofit.** { *; }
-keepattributes Signature
-keepattributes Exceptions

# Gson
-keep class com.google.gson.** { *; }
-keepattributes SourceFile,LineNumberTable
-keepattributes *Annotation*

# OkHttp
-dontwarn okhttp3.**
-keep class okhttp3.** { *; }

# Kotlin
-keep class kotlin.** { *; }
-dontwarn kotlin.**
-keepclassmembers class kotlin.Metadata {
    public <methods>;
}

# Models
-keep class com.medicallab.reports.data.models.** { *; }
-keep class com.medicallab.reports.data.api.** { *; }

# View Models
-keep class com.medicallab.reports.presentation.viewmodel.** { *; }

# Hilt
-keep class dagger.hilt.** { *; }
-keep class * extends dagger.hilt.android.lifecycle.HiltViewModel { *; }

# Glide
-keep public class * implements com.bumptech.glide.load.ResourceDecoder
-keep public class * implements com.bumptech.glide.load.ResourceEncoder

# Room
-keep class * extends androidx.room.RoomDatabase
-keep @androidx.room.Entity class *
-keepclassmembers class * extends androidx.room.RoomDatabase {
    public static ** getInstance(...);
}
