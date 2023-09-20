<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Transaction;
use App\Models\UpgradeRequest;
use App\Models\User;
use App\Models\WebConfig;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function User(Request $request)
    {
        $user = User::paginate(5);

        if (strlen($request->status) > 0) {
            if (strlen($request->name) > 0) {
                $user = User::where('is_banned', !$request->status)->where('name', 'like', '%' . $request->name . '%')->paginate(5)->appends(request()->query());
            } else {
                $user = User::where('is_banned', !$request->status)->paginate(5)->appends(request()->query());
            }
        }
        return view('admin.frontend.users.user', compact('user', 'request'));
    }

    public function showAdmin()
    {
        $user = User::where('rights', '>=', '5')->paginate(5);
        return view('admin.frontend.users.admin', compact('user'));
    }

    public function showSeller()
    {
        $user = User::where('rights', '=', '3')->paginate(5);
        return view('admin.frontend.users.admin', compact('user'));
    }

    public function upgradeRequests()
    {
        $requests = UpgradeRequest::where('status', 0)->paginate(10);
        return view('admin.frontend.users.upgrade', compact(
            'requests'
        ));
    }

    public function upgradeRequestsApprove($id)
    {
        $requestUpg = UpgradeRequest::find($id);
        if (!$requestUpg) abort(404);

        $requestUpg->status = 1;
        $requestUpg->save();

        $requestUpg->user->rights = 3;
        $requestUpg->user->save();

        $log = new ActivityLog();
        $log->user_id = auth()->user()->id;
        $log->detail = "Upgrade Request #" . $id . " đã được chấp nhận bởi " . auth()->user()->name;
        $log->save();

        return response()->json([
            'success' => true,
            'message' => 'Chấp nhận yêu cầu thành công'
        ]);
    }

    public function upgradeRequestsReject($id)
    {
        $requestUpg = UpgradeRequest::find($id);
        if (!$requestUpg) abort(404);

        $requestUpg->status = -1;
        $requestUpg->save();

        $generalSettings = WebConfig::first();
        $requestUpg->user->balance += $generalSettings->upgrade_fee;
        $requestUpg->user->save();

        $trans = new Transaction();
        $trans->user_id = $requestUpg->user->id;
        $trans->amount = $generalSettings->upgrade_fee;
        $trans->balance = $requestUpg->user->balance;
        $trans->note = "Từ chối yêu cầu nâng cấp người bán";
        $trans->type = '+';
        $trans->status = 1;
        $trans->save();

        $log = new ActivityLog();
        $log->user_id = auth()->user()->id;
        $log->detail = "Upgrade Request #" . $id . " đã bị từ chối bởi " . auth()->user()->name;
        $log->save();

        return response()->json([
            'success' => true,
            'message' => 'Từ chối yêu cầu thành công'
        ]);
    }

    public function editUser($id)
    {
        $user = User::find($id);
        return view('admin.frontend.user.edit', ['user' => $user]);
    }
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if ($user->rights >= auth()->user()->rights) {
            return redirect()->back()->withErrors(['message' => 'Bạn không thể chỉnh sửa người dùng có chức vụ cao hơn hoặc ngang hàng bạn.']);
        }
        if ($request->input('rights') > auth()->user()->rights) {
            return redirect()->back()->withErrors(['message' => 'Bạn không thể trao quyền hạn cao hơn bạn.']);
        }
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->payment = $request->input('payment');
        $user->balance = $request->input('balance');
        $user->is_banned = intval($request->input('is_banned'));
        $user->rights = $request->input('rights');
        $user->save();

        $log = new ActivityLog();
        $log->user_id = auth()->user()->id;
        $log->detail = "User #" . $user->id . " đã được chỉnh sửa bởi " . auth()->user()->name;
        $log->save();

        return redirect()->back()->with('success', 'Cập nhật thành công.');
    }
    public function banUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Người dùng không tồn tại!'
            ]);
        }
        $user->is_banned = 1;
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Đã cấm người dùng!.'
        ]);
    }
    public function searchUser(Request $request)
    {
        $search = $request->input('search');
        $user = User::where('name', 'like', '%' . $search . '%')
            ->orWhere('rights', 'like', '%' . $search . '%')
            ->get();
        return view('admin.frontend.user', ['user' => $user]);
    }
}