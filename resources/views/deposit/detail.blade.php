@include('layouts.header')

<div class="container mx-auto my-3 bg-white rounded-lg">
    <div class="flex flex-col p-3">
        <h1 class="p-3 text-lg font-medium">{{$gateway->name}}</h1>
        <p class="p-3">{{$gateway->description}}</p>
        <div class="flex flex-col items-center justify-center p-5">
            {!! $gateway->content !!}
            <p class="p-2">Lời nhắn (nếu có): <b>CK {{auth()->user()->id}}</b></p>
            <p class="p-2">Số tiền nạp trong khoảng: <b>{{number_format($gatewayCurrency->min_amount)}}</b> -
                <b>{{number_format($gatewayCurrency->max_amount)}}</b> VNĐ
            </p>
            <form action="{{route('user.deposit.preview')}}" method="POST">
                @csrf
                <input type="hidden" value="{{$gateway->id}}" name="gateway" placeholder=" " />
                <div class="relative z-0 pb-3">
                    <input type="number" id="floating_standard"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        name="amount" placeholder=" " />
                    <label for="floating_standard"
                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nhập
                        số tiền cần nạp</label>
                </div>

                <button type="submit"
                    class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">Nạp
                    tiền</button>
            </form>
        </div>
    </div>
</div>

@include('layouts.footer')