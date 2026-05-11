<div class="p-6">

    <h1 class="text-2xl font-bold mb-5">
        Gestion des utilisateurs
    </h1>

    <table class="w-full border">

        <tr class="bg-gray-200">
            <th class="p-2">Nom</th>
            <th>Email</th>
            <th>Role</th>
        </tr>

        @foreach($users as $user)

            <tr class="border">
                <td class="p-2">
                    {{ $user->name }}
                </td>

                <td>
                    {{ $user->email }}
                </td>

                <td>
                    {{ $user->role }}
                </td>
            </tr>

        @endforeach

    </table>

</div>