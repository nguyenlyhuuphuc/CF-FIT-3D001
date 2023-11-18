<h1>View PHP</h1>
@php 
    $name = 'Nguyen Van a';
    $html = '<h1>HTML</h1>'
@endphp 


{{ $name }}
<?= $html ?>

{{ $html }}
{!! $html !!}


@php 
    $scores = [1, 8, 5, 2, 3, 7, 6];
    $scores = [];
@endphp

<table border="1">
    <tr>
        <th>STT</th>
        <th>Diem</th>
        <th>Ket Qua</th>
    </tr>
        

    @forelse ($scores as $score)
        @php 
            //  $style = $loop->even ? "style='background: grey'" : '';
            $style = $loop->odd ? '' : "style='background: grey'";
        @endphp
        <tr <?= $style ?>>
            <td><?= $loop->iteration ?></td>
            <td><?= $score ?></td>
            <td>
                @if($score < 5)
                    khong dat
                @else
                    dat
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="3">No date</td>
        </tr>
    @endforelse
</table>

