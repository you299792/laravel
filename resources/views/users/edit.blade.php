<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - {{ $user->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #0f172a; --accent: #3b82f6; --accent-light: #60a5fa; --warning: #f59e0b; --danger: #ef4444; --light: #f8fafc; --border: #e2e8f0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%); color: var(--primary); min-height: 100vh; }
        .navbar { background: white; border-bottom: 2px solid var(--border); box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 1.2rem 0; }
        .navbar-container { max-width: 1400px; margin: 0 auto; padding: 0 2rem; display: flex; }
        .navbar-brand { font-size: 1.6rem; font-weight: 800; background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
        .navbar-brand i { -webkit-text-fill-color: var(--accent); font-size: 1.8rem; }
        .container-wrapper { max-width: 700px; margin: 0 auto; padding: 3rem 2rem; }
        .back-link { color: var(--accent); text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem; transition: all 0.2s ease; }
        .back-link:hover { color: var(--accent-light); transform: translateX(-4px); }
        .form-card { background: white; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid var(--border); }
        .form-header { background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%); color: white; padding: 2rem; display: flex; align-items: center; gap: 1rem; }
        .form-header i { font-size: 2rem; }
        .form-header h1 { margin: 0; font-size: 1.75rem; font-weight: 800; }
        .form-content { padding: 2.5rem; }
        .form-group { margin-bottom: 1.75rem; }
        label { display: block; margin-bottom: 0.75rem; font-weight: 700; color: var(--primary); font-size: 0.95rem; text-transform: uppercase; }
        input { width: 100%; padding: 0.95rem; border: 2px solid var(--border); border-radius: 0.75rem; font-size: 1rem; font-family: inherit; transition: all 0.3s ease; background: white; color: var(--primary); }
        input:focus { outline: none; border-color: var(--warning); box-shadow: 0 0 0 4px rgba(245,158,11,0.1); }
        .error { color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem; }
        .button-group { display: flex; gap: 1rem; margin-top: 2.5rem; }
        .btn-primary { flex: 1; background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%); color: white; padding: 1rem; border: none; border-radius: 0.75rem; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.75rem; box-shadow: 0 4px 15px rgba(245,158,11,0.2); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(245,158,11,0.35); color: white; }
        .btn-secondary { flex: 1; background: var(--light); color: var(--primary); padding: 1rem; border: 2px solid var(--border); border-radius: 0.75rem; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 0.75rem; }
        .btn-secondary:hover { background: var(--border); transform: translateY(-2px); }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('users.index') }}" class="navbar-brand">
                <i class="fas fa-users"></i>
                <span>UserHub</span>
            </a>
        </div>
    </nav>
    <div class="container-wrapper">
        <a href="{{ route('users.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back to Users</a>
        <div class="form-card">
            <div class="form-header">
                <i class="fas fa-user-edit"></i>
                <div>
                    <h1>Edit User</h1>
                    <p style="margin:0; color:rgba(255,255,255,0.9);">Update user information</p>
                </div>
            </div>
            <div class="form-content">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="form-group">
                        <label for="name"><i class="fas fa-user" style="margin-right:0.5rem;"></i>Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter full name" value="{{ old('name', $user->name) }}" class="@error('name') is-invalid @enderror">
                        @error('name')<div class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope" style="margin-right:0.5rem;"></i>Email Address</label>
                        <input type="email" id="email" name="email" placeholder="user@example.com" value="{{ old('email', $user->email) }}" class="@error('email') is-invalid @enderror">
                        @error('email')<div class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                        <a href="{{ route('users.index') }}" class="btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
