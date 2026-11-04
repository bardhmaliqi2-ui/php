
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In Form</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #667eea, #764ba2);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: Arial, sans-serif;
      padding: 30px 15px;
    }

    .form-card {
      background: #fff;
      width: 100%;
      max-width: 750px;
      padding: 40px;
      border-radius: 18px;
      box-shadow: 0 10px 35px rgba(0,0,0,0.2);
    }

    .form-card h2 {
      text-align: center;
      font-weight: 700;
      color: #333;
      margin-bottom: 10px;
    }

    .form-card p {
      text-align: center;
      color: #777;
      margin-bottom: 30px;
    }

    .form-label {
      font-weight: 600;
      color: #444;
    }

    .form-control, .form-select {
      padding: 12px;
      border-radius: 8px;
      border: 1px solid #ddd;
    }

    .form-control:focus, .form-select:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
    }

    .btn-custom {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn-custom:hover {
      opacity: 0.9;
      color: white;
      transform: translateY(-2px);
    }
  </style>
</head>

<body>

  <div class="form-card">
    <h2>Create Account</h2>
    <p>Fill in your information below</p>

    <form>
      <div class="row mb-3">
        <div class="col-md-6 mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" placeholder="Enter your email">
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" placeholder="Enter password">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Address</label>
        <input type="text" class="form-control" placeholder="1234 Main St">
      </div>

      <div class="mb-3">
        <label class="form-label">Address 2</label>
        <input type="text" class="form-control" placeholder="Apartment, studio, or floor">
      </div>

      <button type="submit" class="btn btn-custom w-100">
        Sign In
      </button>
    </form>
  </div>
</body>
</html>