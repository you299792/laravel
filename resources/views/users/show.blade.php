<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #0f172a; --accent: #3b82f6; --accent-light: #60a5fa; --success: #10b981; --danger: #ef4444; --warning: #f59e0b; --light: #f8fafc; --border: #e2e8f0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%); color: var(--primary); min-height: 100vh; }
        .navbar { background: white; border-bottom: 2px solid var(--border); box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 1.2rem 0; position: sticky; top: 0; z-index: 100; }
        .navbar-container { max-width: 1400px; margin: 0 auto; padding: 0 2rem; }
        .navbar-brand { font-size: 1.6rem; font-weight: 800; background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
        .navbar-brand i { -webkit-text-fill-color: var(--accent); font-size: 1.8rem; }
        .container-wrapper { max-width: 1000px; margin: 0 auto; padding: 3rem 2rem; }
        .back-link { color: var(--accent); text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem; transition: all 0.2s ease; }
        .back-link:hover { color: var(--accent-light); transform: translateX(-4px); }
        .user-card { background: white; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid var(--border); }
        .user-header { background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%); color: white; padding: 3rem 2rem; display: flex; align-items: center; gap: 2rem; }
        .user-avatar { width: 100px; height: 100px; background: rgba(255,255,255,0.2); border: 3px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; flex-shrink: 0; }
        .user-header h1 { margin: 0; font-size: 2rem; font-weight: 800; }
        .user-header p { margin: 0.5rem 0 0 0; opacity: 0.9; font-size: 0.95rem; }
        .user-content { padding: 3rem 2rem; }
        .user-meta { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-bottom: 3rem; padding-bottom: 3rem; border-bottom: 2px solid var(--border); }
        .meta-label { font-weight: 700; color: var(--primary); font-size: 0.85rem; text-transform: uppercase; margin-bottom: 0.75rem; }
        .meta-value { color: #64748b; font-size: 1.05rem; word-break: break-word; }
        .verified-badge { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46; padding: 0.5rem 1.25rem; border-radius: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem; }
        .not-verified-badge { background: linear-gradient(135deg, #fee2e2 0%, #fca5a5 100%); color: #991b1b; padding: 0.5rem 1.25rem; border-radius: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem; }
        .action-buttons { display: flex; gap: 1rem; flex-wrap: wrap; }
        .btn-action { padding: 0.9rem 1.75rem; border: none; border-radius: 0.75rem; cursor: pointer; font-size: 0.95rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.75rem; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .btn-verify { background: linear-gradient(135deg, var(--success) 0%, #059669 100%); color: white; }
        .btn-verify:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(16,185,129,0.3); color: white; }
        .btn-edit { background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%); color: white; }
        .btn-edit:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(245,158,11,0.3); color: white; }
        .btn-delete { background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%); color: white; }
        .btn-delete:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(239,68,68,0.3); color: white; }
        .btn-back { background: var(--light); color: var(--primary); border: 2px solid var(--border); }
        .btn-back:hover { background: var(--border); transform: translateY(-3px); color: var(--primary); }
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
        <div class="user-card">
            <div class="user-header">
                <div class="user-avatar">{{ substr($user->name, 0, 1) }}</div>
                <div>
                    <h1>{{ $user->name }}</h1>
                    <p>{{ $user->email }}</p>
                </div>
            </div>
            <div class="user-content">
                <div class="user-meta">
                    <div>
                        <span class="meta-label"><i class="fas fa-envelope"></i> Email Address</span>
                        <span class="meta-value">{{ $user->email }}</span>
                    </div>
                    <div>
                        <span class="meta-label"><i class="fas fa-check-circle"></i> Verification Status</span>
                        @if($user->email_verified_at)
                            <span class="verified-badge"><i class="fas fa-check"></i> Verified on {{ $user->email_verified_at->format("M d, Y") }}</span>
                        @else
                            <span class="not-verified-badge"><i class="fas fa-exclamation"></i> Not Verified</span>
                        @endif
                    </div>
                    <div>
                        <span class="meta-label"><i class="fas fa-calendar-alt"></i> Member Since</span>
                        <span class="meta-value">{{ $user->created_at->format("M d, Y") }}</span>
                    </div>
                    <div>
                        <span class="meta-label"><i class="fas fa-history"></i> Last Updated</span>
                        <span class="meta-value">{{ $user->updated_at->format("M d, Y H:i A") }}</span>
                    </div>
                </div>
                <div class="action-buttons">
                    @if(!$user->email_verified_at)
                        <form action="{{ route('users.verify', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-action btn-verify"><i class="fas fa-check-double"></i> Verify Email</button>
                        </form>
                    @endif
                    <a href="{{ route('users.edit', $user->id) }}" class="btn-action btn-edit"><i class="fas fa-edit"></i> Edit User</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method("DELETE")
                        <button type="submit" class="btn-action btn-delete" onclick="return confirm('Delete this user permanently?')"><i class="fas fa-trash-alt"></i> Delete</button>
                    </form>
                    <a href="{{ route('users.index') }}" class="btn-action btn-back"><i class="fas fa-arrow-left"></i> Back to Users</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
