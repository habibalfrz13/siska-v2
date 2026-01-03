# SISKA Mobile API Documentation

## Base URL
```
https://your-domain.com/api/v1
```

## Authentication
API menggunakan **Laravel Sanctum** untuk autentikasi token.

### Headers
```http
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

---

## Endpoints

### Authentication

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| POST | `/auth/register` | Register user baru | ❌ |
| POST | `/auth/login` | Login dan dapatkan token | ❌ |
| GET | `/auth/me` | Get user profile | ✅ |
| POST | `/auth/logout` | Logout (revoke token) | ✅ |
| POST | `/auth/logout-all` | Logout semua device | ✅ |
| POST | `/auth/change-password` | Ganti password | ✅ |

---

### Kelas

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| GET | `/kelas` | List semua kelas (dengan filter) | ❌ |
| GET | `/kelas/{id}` | Detail kelas | ❌ |
| GET | `/kelas/available/list` | Kelas yang tersedia untuk user | ✅ |
| POST | `/kelas/{id}/enroll` | Daftar ke kelas | ✅ |
| GET | `/my-classes` | Kelas yang diikuti user | ✅ |

**Admin Only:**
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/admin/kelas` | Tambah kelas baru |
| PUT | `/admin/kelas/{id}` | Update kelas |
| DELETE | `/admin/kelas/{id}` | Hapus kelas |

---

### Transaksi

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| GET | `/transaksi` | List transaksi user | ✅ |
| GET | `/transaksi/{id}` | Detail transaksi | ✅ |
| GET | `/transaksi/pending/{kelasId}` | Cek transaksi pending | ✅ |
| POST | `/transaksi/{id}/cancel` | Batalkan transaksi | ✅ |

**Admin Only:**
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin/transaksi/all` | Semua transaksi |
| GET | `/admin/transaksi/statistics` | Statistik transaksi |
| POST | `/admin/transaksi/{id}/confirm` | Konfirmasi pembayaran |

---

## Request/Response Examples

### Register
```http
POST /api/v1/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "Password123",
  "password_confirmation": "Password123"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Registrasi berhasil.",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "user"
    },
    "token": "1|abc123...",
    "token_type": "Bearer"
  }
}
```

### Login
```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "Password123"
}
```

### Get Available Classes
```http
GET /api/v1/kelas?status=Aktif&per_page=10&search=web
Authorization: Bearer {token}
```

**Query Parameters:**
- `status` - Filter by status (Aktif/Tidak Aktif)
- `kategori_id` - Filter by kategori
- `search` - Search by judul
- `per_page` - Items per page (default: 15)
- `sort_by` - Sort field (default: created_at)
- `sort_order` - asc/desc (default: desc)

---

## Error Responses

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Unauthorized. Please login first."
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "Access denied. Insufficient permissions."
}
```

### 422 Validation Error
```json
{
  "success": false,
  "message": "Validasi gagal.",
  "errors": {
    "email": ["Email sudah terdaftar."],
    "password": ["Password minimal 8 karakter."]
  }
}
```

### 429 Too Many Requests
```json
{
  "success": false,
  "message": "Terlalu banyak percobaan login. Silakan coba lagi dalam 60 detik.",
  "retry_after": 60
}
```

---

## Health Check
```http
GET /api/health
```
**Response:**
```json
{
  "status": "ok",
  "message": "SISKA API is running",
  "version": "1.0.0",
  "timestamp": "2026-01-02T23:45:00+07:00"
}
```
