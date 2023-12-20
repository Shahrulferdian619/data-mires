<table>

    <tr>
        <td></td>
        <td colspan="5" ></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="5" height="50" style="text-align: center;font-size: 20px;vertical-align:center;" > 
            <strong>PT MIRES MAHISA GLOBALINDO</strong> </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="5" height="20" style="text-align: center;font-size: 7px;vertical-align:center;" > Jl. Raya Menganti 27 C, Forest Mansion, Cluster Blossom Hill B - 08 Kel.Lidah Wetan, Kec Lakarsantri - Surabaya, Indonesia </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="5" ></td>
    </tr>
    @foreach ($gl as $item)
        <tr>
            <td></td>
            <td colspan="2" height="20" style="font-size: 8px;vertical-align:center;" ><strong>Nomer</strong></td>
            <td colspan="3" height="20" style="font-size: 8px;vertical-align:center;" ><strong>: {{ $item['nomer'] }}</strong></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" height="20" style="font-size: 8px;vertical-align:center;"><strong>Tahun</strong></td>
            <td colspan="3" height="20" style="font-size: 8px;vertical-align:center;"><strong>: {{ $item['tahun'] }}</strong></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" height="20" style="font-size: 8px;vertical-align:center;"><strong>Tanggal</strong></td>
            <td colspan="3" height="20" style="font-size: 8px;vertical-align:center;"><strong>: {{ $item['tanggal'] }}</strong></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="5" height="20" style="font-size: 8px;vertical-align:center;" > <strong>RINCIAN</strong> </td>
        </tr>
        <tr>
            <td></td>
            <td width="5" height="20" style="text-align: center;font-size: 10px;vertical-align:center;background-color:black;color:white" ><strong>No.</strong></td>
            <td width="20" height="20" style="text-align: center;font-size: 10px;vertical-align:center;background-color:black;color:white" ><strong>Nomer Akun</strong></td>
            <td width="25" height="20" style="text-align: center;font-size: 10px;vertical-align:center;background-color:black;color:white" ><strong>Nama Akun</strong></td>
            <td width="30" height="20" style="text-align: center;font-size: 10px;vertical-align:center;background-color:black;color:white" ><strong>Debit</strong></td>
            <td width="30" height="20" style="text-align: center;font-size: 10px;vertical-align:center;background-color:black;color:white" ><strong>Kredit</strong></td>
        </tr>
        @foreach ($item['detail'] as $key => $itemDetail)
            <tr>
                <td></td>
                <td height="20" style="text-align: center;font-size: 10px;vertical-align:center;" > {{ $key+1 }} </td>
                <td height="20" style="text-align: center;font-size: 10px;vertical-align:center;" > {{ $itemDetail['nomer_akun'] }} </td>
                <td height="20" style="text-align: center;font-size: 10px;vertical-align:center;" > {{ $itemDetail['nama_akun'] }} </td>
                <td height="20" style="font-size: 10px;vertical-align:center;" > {{ $itemDetail['debit'] }} </td>
                <td height="20" style="font-size: 10px;vertical-align:center;" > {{ $itemDetail['kredit'] }} </td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td colspan="5" ></td>
        </tr>
    @endforeach

</table>