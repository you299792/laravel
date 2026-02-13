<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #0f172a; --accent: #3b82f6; --accent-light: #60a5fa; --success: #10b981; --danger: #ef4444; --warning: #f59e0b; --light: #f8fafc; --border: #e2e8f0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%); color: var(--primary); }
        .navbar { background: white; border-bottom: 2px solid var(--border); box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 1.2rem 0; position: sticky; top: 0; z-index: 100; }
        .navbar-container { max-width: 1400px; margin: 0 auto; padding: 0 2rem; display: flex; }
        .navbar-brand { font-size: 1.6rem; font-weight: 800; background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
        .navbar-brand i { -webkit-text-fill-color: var(--accent); font-size: 1.8rem; }
        .container-wrapper { max-width: 1400px; margin: 0 auto; padding: 3rem 2rem; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; }
        .page-header h1 { font-size: 2.5rem; font-weight: 800; margin: 0; }
        .btn-create { background: linear-gradient(135deg, var(--success) 0%, #059669 100%); color: white; padding: 0.9rem 2rem; border: none; border-radius: 0.75rem; text-decoration: none; font-weight: 700; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 15px rgba(16,185,129,0.2); }
        .btn-create:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(16,185,129,0.35); color: white; }
        .alert-box { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border: 1px solid #6ee7b7; border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 2rem; color: #065f46; font-weight: 600; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 12px rgba(16,185,129,0.1); }
        .table-card { background: white; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid var(--border); }
        table thead { background: linear-gradient(135deg, var(--primary) 0%, #1e293b 100%); }
        table th { padding: 1.25rem; font-weight: 700; color: white; text-align: left; font-size: 0.95rem; text-transform: uppercase; }
        table td { padding: 1.25rem; border-bottom: 1px solid var(--border); color: #475569; }
        table tbody tr:hover { background-color: #f8fafc; }
        .user-id { background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%); color: white; padding: 0.35rem 0.9rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; display: inline-block; }
        .user-name { font-weight: 700; color: var(--primary); }
        .verified-badge { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46; padding: 0.35rem 0.9rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; }
        .not-verified-badge { background: linear-gradient(135deg, #fee2e2 0%, #fca5a5 100%); color: #991b1b; padding: 0.35rem 0.9rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; }
        .action-buttons { display: flex; gap: 0.6rem; flex-wrap: wrap; }
        .btn-sm { padding: 0.55rem 1rem; border: none; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700; cursor: pointer; text-decoration: none; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 0.4rem; }
        .btn-view { background: var(--light); color: var(--accent); border: 1.5px solid var(--border); }
        .btn-view:hover { background: var(--accent); color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59,130,246,0.3); }
        .btn-verify { background: linear-gradient(135deg, var(--success) 0%, #059669 100%); color: white; }
        .btn-verify:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(16,185,129,0.3); }
        .btn-edit { background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%); color: white; }
        .btn-edit:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(245,158,11,0.3); }
        .btn-delete { background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%); color: white; }
        .btn-delete:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(239,68,68,0.3); }
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
        <div class="page-header">
            <div>
                <h1>Users Directory</h1>
                <p style="color: #64748b; font-size: 1.1rem;">Manage and organize your user database</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn-create"><i class="fas fa-plus"></i> Add New User</a>
        </div>
        @if(session('success'))
            <div class="alert-box">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        @if($users->count())
            <div class="table-card">
                <div style="overflow-x: auto;">
                    <table style="margin:0; width:100%; border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th style="width:8%"><i class="fas fa-hashtag"></i> ID</th>
                                <th style="width:25%"><i class="fas fa-user"></i> Name</th>
                                <th style="width:30%"><i class="fas fa-envelope"></i> Email</th>
                                <th style="width:18%"><i class="fas fa-check"></i> Status</th>
                                <th style="width:15%"><i class="fas fa-calendar"></i> Joined</th>
                                <th style="width:20%"><i class="fas fa-cog"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td><span class="user-id">#{{ $user->id }}</span></td>
                                    <td><span class="user-name">{{ $user->name }}</span></td>
                                    <td>{{ $user->email }}</td>
                                    <td>@if($user->email_verified_at)<span class="verified-badge"><i class="fas fa-check"></i> Verified</span>@else<span class="not-verified-badge"><i class="fas fa-exclamation"></i> Pending</span>@endif</td>
                                    <td>{{ $user->created_at->format("M d, Y") }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('users.show', $user->id) }}" class="btn-sm btn-view"><i class="fas fa-eye"></i> View</a>
                                            @if(!$user->email_verified_at)
                                                <form action="{{ route('users.verify', $user->id) }}" method="POST" style="display:inline;">@csrf<button type="submit" class="btn-sm btn-verify" onclick="return confirm('Verify this email?')"><i class="fas fa-check"></i> Verify</button></form>
                                            @endif
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn-sm btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">@csrf @method("DELETE")<button type="submit" class="btn-sm btn-delete" onclick="return confirm('Delete this user?')"><i class="fas fa-trash"></i> Delete</button></form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="table-card">
                <div style="text-align:center; padding:4rem 2rem;">
                    <div style="font-size:4rem; margin-bottom:1.5rem; opacity:0.5;"></div>
                    <p style="color:#64748b; font-size:1.1rem; margin-bottom:2rem;">No users yet. Create your first user!</p>
                    <a href="{{ route('users.create') }}" class="btn-create"><i class="fas fa-plus"></i> Create First User</a>
                </div>
            </div>
        @endif
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
