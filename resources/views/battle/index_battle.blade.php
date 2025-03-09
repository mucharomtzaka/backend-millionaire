<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Battle Quiz') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($data)
                        <div id="joinGroup" class='text-2xl'>
                            Group Join: {{ $data?->group->name }}
                        </div>
                        <div
                        class='bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-red-50 mt-4 p-4' id="realtimeResult">
                            {{-- code realtime --}}
                            <div class='text-center'>The Question will be show here </div>
                            {{-- end code realtime  --}}
                        </div>
                    @elseif (!$data)
                        <form id="joinForm">
                            <div>
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="old('name')" required autofocus autocomplete="name" placeholder="group name" />
                            </div>
                            <div class="mt-4">
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                    required autofocus autocomplete="false" placeholder="password" />
                            </div>

                            <div class="mt-2 flex">
                                <x-primary-button type="submit" id="submitBtn" class="ms-4">
                                    {{ __('Start') }}
                                </x-primary-button>
                            </div>

                        </form>
                    @endif


                </div>
            </div>
        </div>
        @push('stylecss')
            <style>
                .otp-container {
                    color: black;
                    display: flex;
                    justify-content: center;
                    gap: 10px;
                    margin-top: 10px;
                }

                .otp-box {
                    width: 50px;
                    height: 50px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 24px;
                    font-weight: bold;
                    border: 2px solid #3498db;
                    border-radius: 8px;
                    background-color: #f8f8f8;
                }
            </style>
        @endpush

        @push('javascript')
            <script>
                $(document).ready(function() {
                    $('#joinForm').on('submit', function(e) {
                        e.preventDefault();
                        let submitBtn = $('#submitBtn');
                        submitBtn.prop('disabled', true).text('Processing...');

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: '/joinstore',
                            method: 'POST',
                            data: $(this).serialize(),
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.data.message,
                                });
                                $("#joinGroup").html("Group: " + response.data.group);
                                $('#joinForm')[0].reset();
                                submitBtn.prop('disabled', false).text('Start');
                                location.reload();
                            },
                            error: function(response) {
                                console.log(response);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.responseJSON.message,
                                });
                                submitBtn.prop('disabled', false).text('Start');
                            },
                            complete: function() {
                                submitBtn.prop('disabled', false).text('Start');
                            }
                        });
                    });
                });

                //otp typing
                document.addEventListener("DOMContentLoaded", function() {
                    const inputs = document.querySelectorAll(".otp-input");

                    inputs.forEach((input, index) => {
                        input.addEventListener("input", (e) => {
                            if (e.target.value && index < inputs.length - 1) {
                                inputs[index + 1].focus();
                            }
                        });

                        input.addEventListener("keydown", (e) => {
                            if (e.key === "Backspace" && index > 0 && !e.target.value) {
                                inputs[index - 1].focus();
                            }
                        });
                    });
                });
            </script>

            <script>
                window.addEventListener('DOMContentLoaded', function() {
                    window.Echo.private('group.{{ $data?->group_id }}')
                        .listen('GroupMessageSent', (event) => {
                            $('#realtimeResult').html(`
                                <p>Question:</p>
                        <p id="question" class='text-xl mt-4 text-center'>
                            ${event.message.no}. Clue: " ${event.message.clue} "
                        </p>
                        <div class="flex justify-center items-center w-full mt-2">
                            <img src="${event.message.image}"
                                alt="" width="400" height="400">
                        </div>
                        <div>
                            <div class="mt-4 text-center">
                                ${event.message.word_length} Huruf
                            </div>
                            <div class="otp-container">
                                ${Array.from({ length: event.message.word_length }, (_, i) => `<div class="otp-box" id="otp-${i + 1}"></div>`).join('')}
                            </div>
                        </div>
                        <form id="answerForm" class="text-gray-600 flex-col justify-items-center">
                            <input type="hidden" name="question_id" value="${event.message.id}">
                             <input type="hidden" name="puzzle_id" value="${event.message.puzzle_id}">
                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                            <input type="text" name="answer" id="answer" class="border p-2 w-full mt-4" placeholder="Masukkan Jawaban" required>
                            <span id="char-count" class="text-gray-600 mt-2 block">0 Huruf</span>
                            <button type="submit" class="w-full flex justify-center  text-white px-4 py-2 mt-4" id="btnSaveAnswer" style="background-color:red" id="submitUser">Send</button>
                        </form>

                        `);

                        // Setelah HTML dibuat, isi OTP box berdasarkan posisi
                        let position = event.message.position; // Posisi dimulai dari 1
                        let helper = event.message.word_helper; // Huruf bantuan

                        $(`#otp-${position}`).text(helper);

                    });
                });

                window.addEventListener('DOMContentLoaded', function() {
                    window.Echo.private('answer.{{ $data?->group_id }}')
                        .listen('AnswerSent', (event) => {
                            console.log(event);
                            const word = event.message.word; // Jawaban
                            const wordLength = event.message.word_length; // Panjang kata

                            let otpHtml = Array.from(word).map((char, i) =>
                                    `<div class="otp-box" id="otp-${i + 1}">${char}</div>`
                                ).join('');

                                $('.otp-container').html(otpHtml);
                        });
                });

                //count typing character
                $(document).on('input', '#answer', function () {
                    let length = $(this).val().length;
                    $('#char-count').text(length + ' Huruf');
                });


                // AJAX Submit
                $(document).on('submit', '#answerForm', function (e) {
                    e.preventDefault();
                    let answerBtn = $('#btnSaveAnswer');
                        answerBtn.prop('disabled', true).text('Sending...');
                    let formData = $(this).serialize(); // Ambil data form

                    $.ajax({
                        url: "/battleSubmit",
                        type: "POST",
                        data: formData,
                        success: function (response) {
                            answerBtn.prop('disabled', true).text('Disabled')
                            .css('background-color', 'gray');

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.data.message,
                            }); // Tampilkan pesan sukses
                        },
                        error: function (xhr) {
                            answerBtn.prop('disabled', false).text('Send');
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON.message,
                            });
                        }
                    });
                });
            </script>
        @endpush
    </div>
</x-app-layout>
