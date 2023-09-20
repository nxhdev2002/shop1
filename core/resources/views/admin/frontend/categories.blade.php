@push('script')
<script>
    function update(cateID) {
        let id = $('#cate_id_' + cateID).val()
        let name = $('#name-' + cateID).val()
        let status = $('#status-' + cateID).find(':selected').val()
        let highlight = $('#highlight-' + cateID).is(':checked') ? 1 : 0
        $.ajax({
            url: "/admin/categories/" + id + "/update",
            type: "PUT",
            data: {
                name: name,
                status: status,
                highlight: highlight,
                _token: '{{csrf_token()}}'
            },
            success: function (data) {
                Swal.fire(
                    'Thành công!',
                    data.message,
                    'success'
                )
                setTimeout(() => {
                    window.location.reload();
                }, 1000)
            },
            error: function (error) {
                console.log(error);
                // Xử lý lỗi nếu yêu cầu chấp nhận tiền gửi gặp sự cố
            }
        });
    }
    function closeUpdateCate() {
        const modal = document.getElementById("closeUpdateCate");
        modal.classList.add("modal-close");
        setTimeout(() => {
            modal.classList.remove("modal-close");
            modal.setAttribute("aria-hidden", "true");
            modal.style.display = "none";
            document.body.classList.remove("modal-open");
        }, 300);
    }
</script>
@endpush
@include('admin.layouts.header')
@include('admin.layouts.sidebar')
<main class="p-4 sm:ml-64">
    <div class="mx-6">
        <table class="w-full whitespace-no-wrap">
            <div class="flex flex-row justify-between p-4">
                <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                    Categories List
                </h2>
                <button data-modal-target="defaultModal" data-modal-toggle="defaultModal"
                    class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    type="button">
                    Add New
                </button>
                <div id="defaultModal" tabindex="-1" aria-hidden="true"
                    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="absolute w-full h-full bg-gray-900 opacity-50 modal-overlay"></div>
                    <div
                        class="z-50 w-11/12 mx-auto overflow-y-auto bg-white rounded shadow-lg modal-container md:max-w-md">
                        <div class="px-6 py-4 text-left modal-content">
                            <!-- Nội dung của modal -->
                            <div class="flex items-center justify-between pb-3">
                                <p class="text-2xl font-bold">Thêm mới danh mục</p>
                                <div class="z-50 cursor-pointer modal-close">
                                    <svg class="text-black fill-current" xmlns="http://www.w3.org/2000/svg" width="18"
                                        height="18" viewBox="0 0 18 18">
                                        <path d="M6.6 6l5.4 5.4m0-5.4L6.6 11" />
                                    </svg>
                                </div>
                            </div>

                            <form method="POST" action="{{route('admin.storeCategory')}}">
                                @csrf
                                <!-- Các trường nhập liệu cho danh mục -->
                                <div class="mb-4">
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="category_name">
                                        Tên danh mục
                                    </label>
                                    <input name="name" required
                                        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        id="category_name" type="text" placeholder="Nhập tên danh mục">
                                </div>

                                <!-- Nút lưu và hủy -->
                                <div class="flex justify-end pt-2">
                                    <button type="submit"
                                        class="p-3 px-4 mr-2 text-indigo-500 bg-transparent rounded-lg modal-close hover:bg-gray-100 hover:text-indigo-400">
                                        Lưu
                                    </button>
                                    <button type="button" data-modal-toggle="defaultModal"
                                        class="p-3 px-4 text-white bg-indigo-500 rounded-lg modal-close hover:bg-indigo-400">
                                        Hủy
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Category name</th>
                        <th class="px-4 py-3">Status</th>
                        <!-- <th class="px-4 py-3">Created at</th> -->
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                @foreach($category as $cate)
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">
                            {{$cate->id}}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{$cate->name}}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if ($cate->status == 1)
                            <span
                                class="px-2 py-1 font-semibold leading-tight text-gray-700 rounded-full dark:text-white dark:bg-orange-600">
                                Hiện
                            </span>
                            @endif
                            @if ($cate->status == 0)
                            <span
                                class="px-2 py-1 font-semibold leading-tight text-gray-700 rounded-full dark:text-white dark:bg-orange-600">
                                Ẩn
                            </span>
                            @endif
                        </td>
                        <!-- <td class="px-4 py-3 text-sm">
                                {{$cate->created_at}}
                            </td> -->
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-4 text-sm">
                                <!-- Button to open the edit category modal -->
                                <div class="text-blue-500 hover:text-blue-700">
                                    <button id="updateCateButton{{$cate->id}}"
                                        data-modal-toggle="updateCateModal{{$cate->id}}"
                                        class="block text-black bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                        type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>
                                </div>
                                <div id="updateCateModal{{$cate->id}}" tabindex="-1" aria-hidden="true"
                                    class="fixed top-0 left-0 right-0 z-50 items-center justify-center hidden w-full overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
                                    <div class="relative w-full h-full max-w-2xl p-4 md:h-auto">
                                        <!-- Modal content -->
                                        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                                            <!-- Modal header -->
                                            <div
                                                class="flex items-center justify-between pb-4 mb-4 border-b rounded-t sm:mb-5 dark:border-gray-600">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    Update Product
                                                </h3>
                                                <button type="button" onclick="closeUpdateCate()"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                                    data-modal-toggle="updateCateModal{{$cate->id}}">
                                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <!-- Modal body -->

                                            <div class="grid gap-4 mb-4 sm:grid-cols-2">
                                                <div>
                                                    <label for="name"
                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category
                                                        Name</label>
                                                    <input type="text" name="name" id="name-{{$cate->id}}"
                                                        value="{{$cate->name}}"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                        placeholder="">
                                                </div>
                                                <div>
                                                    <label for="status"
                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                                    <select id="status-{{$cate->id}}" name="status"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                        @if ($cate->status)
                                                        <option selected value="1">Hiện</option>
                                                        <option value="0">Ẩn</option>
                                                        @else
                                                        <option value="1">Hiện</option>
                                                        <option value="0" selected>Ẩn</option>
                                                        @endif
                                                    </select>
                                                    <input type="hidden" id="cate_id_{{$cate->id}}" name="id"
                                                        value="{{$cate->id}}">
                                                </div>

                                                <label class="relative inline-flex items-center mr-5 cursor-pointer">
                                                    <input type="checkbox" value="1" class="sr-only peer"
                                                        id="highlight-{{$cate->id}}" {{$cate->is_highlight ?
                                                    "checked" : ""}}>
                                                    <div
                                                        class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600">
                                                    </div>
                                                    <span
                                                        class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">High
                                                        Light</span>
                                                </label>

                                            </div>
                                            <div class="flex items-center justify-center space-x-4">
                                                <button type="submit" onclick="update('{{$cate->id}}')"
                                                    class="text-white bg-blue-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                                    Update
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
                @endforeach
        </table>
        <p class="mt-3 text-xs">{{ $category->links()}}</p>
    </div>
</main>