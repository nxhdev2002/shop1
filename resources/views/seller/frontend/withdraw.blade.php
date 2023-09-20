@include('layouts.header')
<div class="flex gap-8">
    @include('seller.frontend.sidebar')
    <main class="w-3/4 h-full overflow-y-auto">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Rút Tiền</h1>
                <p class="text-lg">Số dư: {{number_format($balance)}} VNĐ</p>
            </div>

            <form action="{{route('seller.withdraw.update')}}" method="POST">
                @csrf
                <div class="flex items-center mb-4">
                    <input type="number" name="payment" placeholder="Số tài khoản"
                        class="mr-2 px-4 py-2 border rounded">
                    <input type="number" name="amount" placeholder="Số tiền cần rút"
                        class="mr-2 px-4 py-2 border rounded">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Rút Tiền</button>
                </div>
            </form>

            <div class="mt-8 mb-8">
                <h2 class="text-lg font-bold mb-4">Lịch Sử Giao Dịch</h2>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Ngày
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nội dung
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Trạng thái
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($withDraw as $withdraw)
                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$withdraw->created_at}}
                                </th>

                                <td class="px-6 py-4">
                                    {{$withdraw->note}}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($withdraw->status == 0)
                                    <span
                                        class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600">
                                        Chờ duyệt
                                    </span>
                                    @endif
                                    @if ($withdraw->status == 1)
                                    <span
                                        class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                        Thành công
                                    </span>
                                    @endif
                                    @if ($withdraw->status == 2)
                                    <span
                                        class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">
                                        Bị từ chối
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

@include('layouts.footer')