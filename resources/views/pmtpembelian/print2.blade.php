<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
  <style type="text/css" > 
    *{
      padding: 0;
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
      box-sizing: border-box;
    }
    #kopsurat{
      display: flex;
      flex-direction: row;
      align-items: center;
    }
    #kopsurat img{
      margin: 0px 20px 0px 10px;
    }
    #kopsurat .content h1{
      font-size: 15px;
    }
    #kopsurat .content small{
      font-size: 13px;
    }
    .hr-top{
      border-top: 1px solid black;
    }
    .title{
      text-align: center;
      margin: 10px 0px;
      text-transform: uppercase;
    }
    .document-information{
      padding: 5px 0px;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
    }
    .document-information div{
      width: 40%;
    }
    .document-information table{
      font-size: 10px;
    }
    .document-information .head-document{
      font-size: 10px;
    }
    .document-information .information-box{
      display: flex;
      flex-direction: column;
    }
    .document-information .box-document{
      width: 100%;
      height: 100%;
      border: 2px solid black;
      padding: 0px 5px 5px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .document-information .box-document .title-box{
      font-weight: bold;
      font-size: 10px;
    }
    .document-information .box-document .address-box,
    .document-information .box-document small{
      font-size: 10px;
    }
  </style>
</head>
<body class="A4" >
  <section class="sheet padding-10mm">
    <div style="display: flex;flex-direction: row;align-items: center;" >
      <img style="width:110px;margin: 0px 20px 0px 10px;" src="http://altamasoft.tech/img/logomires.png" alt="">
      <div>
        <p>
          <h1 style="font-size: 15px;" >PT. MIRES MAHISA GLOBALINDO</h1>
          <small style="font-size: 13px;" >JL. Raya Menganti 27 C, FOREST MANISON, Cluster Blossom Hill B-08, Kel</small>
          <br>
          <small style="font-size: 13px;" >Lidah Wetan, Kecamatan Lakarsantri - Surabaya (60211) - Indonesia</small>
          <br>
          <small style="font-size: 13px;" >(081131588881)</small>
        </p>
      </div>
    </div>
    <hr class="hr-top" style="border-top: 1px solid black;" >
    <h1 class="title" style="text-align: center;margin: 10px 0px;text-transform: uppercase;" >Request Order</h1>
    <hr class="hr-top" style="border-top: 1px solid black;" >
    <div class="document-information">
      <div>
        <table >
          <tr>
            <td style="width: 100px" >No. PO</td>
            <td>: <strong>614/RO-PUR/VII/2021</strong> </td>
          </tr>
          <tr>
            <td>Tanggal PO </td>
            <td>: 19 Agustus 2021 </td>
          </tr>
        </table>
      </div>
      <div>
        <table>
          <tr>
            <td style="width: 100px" >Pembayaran </td>
            <td>: Cash </td>
          </tr>
        </table>
      </div>
    </div>
    <div class="document-information">
      <div class="information-box" >
        <span class="head-document" >Supplier</span>
        <div class="box-document" >
          <div style="width: 100%" >
            <span class="title-box">PT. CEDEFINDO</span>
            <p class="address-box">
              JL. Raya Narogong KM 4 Bekasi
            </p>
          </div>
          <div style="width: 100%" >
            <small>Bu Sandra</small>
            <br>
            <small>087878261212</small>
          </div>
        </div>
      </div>
      <div class="information-box">
        <span class="head-document" >Tujuan</span>
        <div class="box-document" >
          <div style="width: 100%;" >
            <span class="title-box">PT. CEDEFINDO</span>
            <p class="address-box">
              JL. Raya Menganti 27 C, FOREST MANISON, Cluster Blossom Hill B-08, Kel Lidah Wetan, Kecamatan Lakarsantri - Surabaya (60211) - Indonesia
            </p>
          </div>
          <div style="width: 100%" >
            <small>Candra</small>
            <br>
            <small>087878261212</small>
          </div>
        </div>
      </div>
    </div>
      <table border="1" style="width: 100%; font-size: 10px; border-collapse: collapse;" >
        <thead>
          <tr>
            <th rowspan="2" style="width: 5%" >No.</th>
            <th rowspan="2" style="width: 10%">NO. PR</th>
            <th rowspan="2" style="width: 25%">Nama Barang</th>
            <th rowspan="2" style="width: 5%">Spesifikasi</th>
            <th rowspan="2" style="width: 5%">Jumlah</th>
            <th rowspan="2" style="width: 5%">Satuan</th>
            <th colspan="2" style="width: 30%">Harga</th>
          </tr>
          <tr>
            <th style="width: 15%" >Satuan</th>
            <th style="width: 15%" >Total</th>
          </tr>
          <tbody>
            {{-- Foreach --}}
            <tr>
              <td style="text-align: center" >1</td>
              <td style="text-align: center" >-</td>
              <td>Handsanitizer</td>
              <td style="text-align: center" >-</td>
              <td style="text-align: center" >50000</td>
              <td style="text-align: center" >pcs</td>
              <td style="text-align: right" >Rp. 7.500</td>
              <td style="text-align: right" >Rp. 375.000.000</td>
            </tr>
            {{-- Foreach --}}
          </tbody>
          <tfoot>
            <tr>
              <td colspan="8" >
                <div style="width: 100%; display: flex; justify-content: space-between; padding: 10px;" >
                  <div style="width: 50%;border: 1px solid black" >
                    <span style="text-decoration: underline; font-size: 10px" >Note: </span>
                    <p style="text-align: center" >
                      untuk produk PT MIRES MAHISA GLOBAL INDO
                    </p>
                  </div>
                  <div style="width: 40%;border: 1px solid black" >
                    <div style="display: flex; justify-content: space-between" >
                      <span>Total</span>
                      <span>375.000.000</span>
                    </div>
                    <div style="display: flex; justify-content: space-between" >
                      <span>PPN 10%</span>
                      <span>37.500.000</span>
                    </div>
                    <br>
                    <br>
                    <div style="display: flex; justify-content: space-between" >
                      <span style="width: 50%;" >Total Akhir</span>
                      <span style="width: 50%;border-top: 1px solid black;text-align:right;" >412.500.000</span>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          </tfoot>
        </thead>
      </table>
    </div>
    <div style="width: 100%;margin:10px 0px;font-size:10px;display: flex;" >
      <div style="width: 60%; border: 1px solid black" >
        <span>Perhatian: </span>
        <ul style="list-style: none" >
          <li>- Barang yang rusak / tidak cocok saat pengiriman akan dikembalikan ( retur )</li>
          <li>- Harap mencantumkan No. PO di invoice dan Delivery Order</li>
          <li>- 1 ( satu ) Invoice hanya berlaku untuk 1 ( satu ) PO</li>
          <li>- Pada saat penagihan harus melampirkan PO asli pada Invoice</li>
          <li>- Harap memberi invoice / nota / kwitansi pada saat pengiriman</li>
        </ul>
      </div>
      <div style="width: 40%; padding: 0px 5px; display: flex;justify-content: space-around" >
        <div style="padding: 0px 5px; " >
          dibuat,
          <br>
          <br>
          <br>
          <br>
          <br>
          <span style="text-decoration: underline" >Putra Rachmad</span>
        </div>
        <div style="padding: 0px 5px; " >
          disetujui,
          <br>
          <br>
          <br>
          <br>
          <br>
          <span style="text-decoration: underline" >Rizky Dermawan</span>
        </div>
      </div>
    </div>
  </section>
</body>
</html>