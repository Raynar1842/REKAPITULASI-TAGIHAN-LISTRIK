/**
 * GOOGLE APPS SCRIPT FOR SISTEM REKAPITULASI TAGIHAN LISTRIK RT 04
 * -----------------------------------------------------------------
 * Petunjuk Penggunaan:
 * 1. Buka Google Sheets baru di https://sheets.google.com
 * 2. Klik menu "Ekstensi" -> "Apps Script".
 * 3. Hapus semua kode bawaan, lalu Salin & Tempel seluruh kode di file ini.
 * 4. Klik tombol "Simpan" (ikon disket).
 * 5. Klik "Terapkan" (Deploy) -> "Terapkan sebagai web app" (New deployment).
 * 6. Ubah pengaturan:
 *    - Pilih Jenis: Web App
 *    - Jalankan Sebagai: Saya (Me)
 *    - Yang Memiliki Akses: Siapa Saja (Anyone) -> SANGAT PENTING!
 * 7. Klik "Terapkan" (Deploy) dan berikan izin akses (Authorize access).
 * 8. Salin URL Web App yang dihasilkan dan masukkan ke dalam aplikasi Web ini pada menu "Google Sheets Settings".
 */

function doGet(e) {
  try {
    var sheet = getOrCreateSheet();
    var data = sheet.getDataRange().getValues();
    
    // Check if sheet is empty or only has headers
    if (data.length <= 1) {
      return responseJSON({
        status: "success",
        data: []
      });
    }
    
    var headers = data[0];
    var wargaList = [];
    
    for (var i = 1; i < data.length; i++) {
      var row = data[i];
      if (!row[0] && !row[1]) continue; // skip empty rows
      
      wargaList.push({
        no: Number(row[0]),
        nama: String(row[1]),
        rek: String(row[2]),
        tagihan: Number(row[3]),
        lunas: row[4] === true || String(row[4]).toLowerCase() === "true" || String(row[4]) === "1"
      });
    }
    
    return responseJSON({
      status: "success",
      data: wargaList
    });
  } catch (error) {
    return responseJSON({
      status: "error",
      message: error.toString()
    });
  }
}

function doPost(e) {
  try {
    var contents = JSON.parse(e.postData.contents);
    var action = contents.action;
    var sheet = getOrCreateSheet();
    
    if (action === "update_status") {
      var no = contents.no;
      var lunas = contents.lunas;
      var data = sheet.getDataRange().getValues();
      var updated = false;
      
      for (var i = 1; i < data.length; i++) {
        if (Number(data[i][0]) === Number(no)) {
          sheet.getRange(i + 1, 5).setValue(lunas);
          updated = true;
          break;
        }
      }
      
      return responseJSON({
        status: updated ? "success" : "not_found",
        message: updated ? "Status berhasil diperbarui." : "Warga tidak ditemukan."
      });
    }
    
    if (action === "reset_all") {
      var lastRow = sheet.getLastRow();
      if (lastRow > 1) {
        // Set column 5 (LUNAS) to false for all data rows
        for (var r = 2; r <= lastRow; r++) {
          sheet.getRange(r, 5).setValue(false);
        }
      }
      return responseJSON({
        status: "success",
        message: "Semua status warga telah direset menjadi BELUM BAYAR."
      });
    }
    
    if (action === "sync_all") {
      var wargaArray = contents.warga || [];
      // Clear data content excluding header
      var lastRow = sheet.getLastRow();
      if (lastRow > 1) {
        sheet.getRange(2, 1, lastRow - 1, 5).clearContent();
      }
      
      var rowsToInsert = [];
      for (var k = 0; k < wargaArray.length; k++) {
        var item = wargaArray[k];
        rowsToInsert.push([
          item.no,
          item.nama,
          item.rek,
          item.tagihan,
          item.lunas === true || String(item.lunas).toLowerCase() === "true"
        ]);
      }
      
      if (rowsToInsert.length > 0) {
        sheet.getRange(2, 1, rowsToInsert.length, 5).setValues(rowsToInsert);
      }
      
      return responseJSON({
        status: "success",
        message: "Data berhasil disinkronkan ke Google Sheets."
      });
    }

    if (action === "add_warga") {
      var newWarga = contents.warga;
      sheet.appendRow([
        newWarga.no,
        newWarga.nama,
        newWarga.rek,
        newWarga.tagihan,
        newWarga.lunas || false
      ]);
      return responseJSON({
        status: "success",
        message: "Warga baru berhasil ditambahkan."
      });
    }

    return responseJSON({
      status: "error",
      message: "Action tidak dikenal: " + action
    });
    
  } catch (error) {
    return responseJSON({
      status: "error",
      message: error.toString()
    });
  }
}

function getOrCreateSheet() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getActiveSheet();
  
  // Ensure headers exist
  if (sheet.getLastRow() === 0) {
    sheet.appendRow(["NO", "NAMA", "NO. REK", "TAGIHAN", "STATUS (LUNAS)"]);
    sheet.getRange("A1:E1").setFontWeight("bold").setBackground("#e2e8f0");
  }
  
  return sheet;
}

function responseJSON(obj) {
  return ContentService.createTextOutput(JSON.stringify(obj))
    .setMimeType(ContentService.MimeType.JSON);
}
