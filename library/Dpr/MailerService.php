<?php

class Dpr_MailerService
{
  public function __construct()
  {
    
    $this->config = array(
      'auth' => 'login',
      'username' => '77a54f0049e91f',
      'password' => '227ba09d58b5c5',
      'port' => '2525',
    );
    $this->tr = new Zend_Mail_Transport_Smtp('smtp.mailtrap.io', $this->config);
    $this->sender = 'noreply@stela.dpr.go.id';
    $this->senderName = 'STELA';
    $this->url = "http://$_SERVER[HTTP_HOST]/";
  }

  private function htmlFormat($title, $content)
  {
    return "<!doctype html>
        <html>
          <head>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
            <title>{$title}</title>
            <style>
        @media only screen and (max-width: 620px) {
          table.body h1 {
            font-size: 28px !important;
            margin-bottom: 10px !important;
          }
        
          table.body p,
        table.body ul,
        table.body ol,
        table.body td,
        table.body span,
        table.body a {
            font-size: 16px !important;
          }
        
          table.body .wrapper,
        table.body .article {
            padding: 10px !important;
          }
        
          table.body .content {
            padding: 0 !important;
          }
        
          table.body .container {
            padding: 0 !important;
            width: 100% !important;
          }
        
          table.body .main {
            border-left-width: 0 !important;
            border-radius: 0 !important;
            border-right-width: 0 !important;
          }
        
          table.body .btn table {
            width: 100% !important;
          }
        
          table.body .btn a {
            width: 100% !important;
          }
        
          table.body .img-responsive {
            height: auto !important;
            max-width: 100% !important;
            width: auto !important;
          }
        }
        @media all {
          .ExternalClass {
            width: 100%;
          }
        
          .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
          }
        
          .apple-link a {
            color: inherit !important;
            font-family: inherit !important;
            font-size: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            text-decoration: none !important;
          }
        
          #MessageViewBody a {
            color: inherit;
            text-decoration: none;
            font-size: inherit;
            font-family: inherit;
            font-weight: inherit;
            line-height: inherit;
          }
        
          .btn-primary table td:hover {
            background-color: #34495e !important;
          }
        
          .btn-primary a:hover {
            background-color: #34495e !important;
            border-color: #34495e !important;
          }
        }
        </style>
          </head>
          <body style='background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;'>
            <span class='preheader' style='color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;'>This is preheader text. Some clients will show this text as a preview.</span>
            <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='body' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f6f6f6; width: 100%;' width='100%' bgcolor='#f6f6f6'>
              <tr>
                <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;' valign='top'></td>
                <td class='container' style='font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; max-width: 580px; padding: 10px; width: 580px; margin: 0 auto;' width='580' valign='top'>
                  <div class='content' style='box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;'>            
                    <table role='presentation' class='main' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: #ffffff; border-radius: 3px; width: 100%;' width='100%'>
                      <tr>
                        <td class='wrapper' style='font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;' valign='top'>
                          <table role='presentation' border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;' width='100%'>
                          <tr>
                          <td style='display: block;
                          margin-left: auto;
                          margin-right: auto;
                          width: 100%;
                          background: linear-gradient(90deg, #457B92 0%, #34ACBC 100%);
    background-image: linear-gradient(90deg, rgb(69, 123, 146) 0%, rgb(52, 172, 188) 100%);
    background-position-x: initial;
    background-position-y: initial;
    background-size: initial;
    background-repeat-x: initial;
    background-repeat-y: initial;
    background-attachment: initial;
    background-origin: initial;
    background-clip: initial;
    background-color: initial;
                          '>
                            <img src='{$this->url}stela/assets/logostela.png'/>
                          </td>
                          </td>
                            <tr>
                              <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;' valign='top'>
                                {$content}
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                    <div class='footer' style='clear: both; margin-top: 10px; text-align: center; width: 100%;'>
                      <table role='presentation' border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;' width='100%'>
                      <tr>
                      <td style='padding:4px;'>
                      <img height='60vh' src='https://stela.dpr.go.id/plugin-parja/assets/img/logo_setjen.png'/>
                      </td>
                      <td style='text-align:left;color: #999999; font-size: 12px;padding:4px'>
                      <p>Telp: (021) 571 56100, (021) 571 56069 <br> 
                      Fax: (021) 571 5702</p>
                      </td>
                      <td style='text-align:left;color: #999999; font-size: 12px;padding:4px;'>
                        <p>Email: pustekinfo@dpr.go.id <br> 
                        Alamat:Jalan Jenderal Gatot Subroto, Senayan, Jakarta 10270</p>
                      </td>
                      <td>
                      </tr>
                      </table>
                      <div class='content-block' style='font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; color: #999999; font-size: 12px; text-align: center;' valign='top' align='center'>
                        <span class='apple-link' style='color: #999999; font-size: 12px; text-align: center;'>Copyright © 2022, made with love by Pusat Teknologi Informasi. All Rights Reserved</span>
                      </div>
                    </div>
                  </div>
                </td>
                <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;' valign='top'></td>
              </tr>
            </table>
          </body>
        </html>";
  }

  protected function sendTo($id_pengguna, $subject, $body)
  {
    try {
      $pengguna = (new Dpr_PenggunaService())->findById($id_pengguna);
      $emailPengguna = $pengguna->getEmail();
      $namaPengguna = $pengguna->getNamaLengkap();
      $mail = new Zend_Mail();
      $mail->setFrom($this->sender, $this->senderName);
      $mail->addTo($emailPengguna, $namaPengguna);
      $mail->setSubject($subject);
      $mail->setBodyHtml($body);
      // $mail->setBodyText($body);
      $mail->send($this->tr);

      return true;
    } catch (Exception $e) {
      return false;
    }
  }

  public function berhasilBuatTiket($id_pengguna, $noTiket)
  {
    $urlStela = "{$this->url}/beranda/captcha/no_tiket/{$noTiket}";

    return $this->sendTo($id_pengguna, "Berhasil Buat Permintaan ({$noTiket})", $this->htmlFormat(
      "Berhasil Buat Permintaan",
      "
        <div class='container'>
        <h1 style='text-align:center;'>Berhasil Buat Permintaan</h1>
        <p>Terima kasih, permintaan akan segera kami proses</p>
        <p>Berikut Informasi Permintaan Anda</p>
        <hr>
        <p>Nomor Tiket : {$noTiket}</p>
        <hr>
        <a class='btn-primary' href={$urlStela}>Detail permintaan</a>
        </div>
        "
    ));
  }

  public function kategoriTiket($id_pengguna, $noTiket)
  {
    $urlStela = "{$this->url}/beranda/captcha/no_tiket/{$noTiket}";

    return $this->sendTo($id_pengguna, "Permintaan Sedang Dalam Proses ({$noTiket})", $this->htmlFormat(
      "Permintaan Sedang Dalam Proses",
      "
        <div class='container'>
        <h1 style='text-align:center;'>Permintaan Sedang Dalam Proses</h1>
        <p>Permintaan sedang kami proses, mohon ditunggu</p>
        <p>Berikut Informasi Permintaan Anda</p>
        <hr>
        <p>Nomor Tiket : {$noTiket}</p>
        <hr>
        <a class='btn-primary' href={$urlStela}>Detail permintaan</a>
        </div>
        "
    ));
  }

  public function selesaiTiket($id_pengguna, $noTiket)
  {
    $urlStela = "{$this->url}/beranda/captcha/no_tiket/{$noTiket}";

    return $this->sendTo($id_pengguna, "Permintaan Selesai ({$noTiket})", $this->htmlFormat(
      "Permintaan Selesai",
      "
        <div class='container'>
        <h1 style='text-align:center;'>Permintaan Selesai</h1>
        <p>Permintaan kamu sudah selesai, jangan lupa memberikan rating ya</p>
        <p>Berikut Informasi Permintaan Anda</p>
        <hr>
        <p>Nomor Tiket : {$noTiket}</p>
        <hr>
        <a class='btn-primary' href={$urlStela}>Detail permintaan</a>
        </div>
        "
    ));
  }

  public function revisiTiketSingrus($id_pengguna, $noTiket, $keteranganRevisi)
  {
    $urlStela = "{$this->url}/beranda/captcha/no_tiket/{$noTiket}";

    return $this->sendTo($id_pengguna, "Permintaan Perlu di Revisi ({$noTiket})", $this->htmlFormat(
      "Permintaan Perlu di Revisi",
      "
        <div class='container'>
        <h1 style='text-align:center;'>Permintaan Perlu di Revisi</h1>
        <p>{$keteranganRevisi}</p>
        <p>Berikut Informasi Permintaan Anda</p>
        <hr>
        <p>Nomor Tiket : {$noTiket}</p>
        <hr>
        <a class='btn-primary' href={$urlStela}>Detail permintaan</a>
        </div>
        "
    ));
  }

  public function verifiedTiketSingrus($id_pengguna, $noTiket, $leader)
  {
    $urlStela = "{$this->url}/beranda/captcha/no_tiket/{$noTiket}";

    return $this->sendTo($id_pengguna, "Permintaan Sudah diverifikasi ({$noTiket})", $this->htmlFormat(
      "Permintaan Sudah diverifikasi",
      "
        <div class='container'>
        <h1 style='text-align:center;'>Permintaan Sudah diverifikasi</h1>
        <p>Permintaan telah diverifikasi dan dikerjakan oleh :</p>
        <p>Nama :{$leader->getNamaLengkap()}</p>
        <p>No. Hp :{$leader->getHp()}</p>
        <p>Berikut Informasi Permintaan Anda</p>
        <hr>
        <p>Nomor Tiket : {$noTiket}</p>
        <hr>
        <a class='btn-primary' href={$urlStela}>Detail permintaan</a>
        </div>
        "
    ));
  }

  public function ubahLeaderSingrus($id_pengguna, $noTiket, $leader)
  {
    $urlStela = "{$this->url}/beranda/captcha/no_tiket/{$noTiket}";

    return $this->sendTo($id_pengguna, "Perubahan Leader Programmer ({$noTiket})", $this->htmlFormat(
      "Perubahan Leader Programmer",
      "
        <div class='container'>
        <h1 style='text-align:center;'>Perubahan Leader Programmer</h1>
        <p>Leader pada permintaan diubah menjadi :</p>
        <p>Nama :{$leader->getNamaLengkap()}</p>
        <p>No. Hp :{$leader->getHp()}</p>
        <p>Berikut Informasi Permintaan Anda</p>
        <hr>
        <p>Nomor Tiket : {$noTiket}</p>
        <hr>
        <a class='btn-primary' href={$urlStela}>Detail permintaan</a>
        </div>
        "
    ));
  }

  public function solusiTiket($id_pengguna, $noTiket, $permasalahanAkhir, $solusi, $email, $noHp)
  {
      $urlStela = "{$this->url}/beranda/captcha/no_tiket/{$noTiket}";

      return $this->sendTo($id_pengguna, "Solusi ({$noTiket})", $this->htmlFormat(
          "Solusi",
          "
        <div class='container'>
        <h1 style='text-align:center;'>Solusi</h1>
        <p>Permasalahan Akhir : {$permasalahanAkhir}</p>
        <hr>
        <p>Berikut Solusi yang Dapat Dicoba : </p>
        <p>{$solusi}</p>
        <hr>
        <p>Berikut Informasi tiket anda</p>
        <hr>
        <p>Nomor Tiket : {$noTiket}</p>
        <hr>
        <a class='btn-primary' href={$urlStela}>Detail permintaan</a>
        <p>Krim Feedback Melalui {$noHp} atau {$email} Agar Kami Dapat Melakukan Tindakan Lebih Lanjut</p>
        </div>
        "
      ));
  }
}
