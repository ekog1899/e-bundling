<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['database_sipp']='sipp';
$config['namapa_pendek']='PA Kisaran';
/*
konfigurasi dibawah ini setting awal yang akan dimulai dinotifikasi;
*/
$config['mulai_tgl_daftar']='2019-10-01'; //mulai sejak tanggal akan di notifikasi format tanggal= 'tahun-bulan-tanggal' atau 'Y-m-d';
$config['mulai_tgl_ac']='2019-10-01'; //mulai sejak tanggal [mulai_tgl_ac] notifikasi akan dikirim, format tanggal= 'tahun-bulan-tanggal' atau 'Y-m-d', tanggal dan bulan format 2 digit
$config['mulai_notif_ac']=0; //artinya notifikasi akan dikirim setelah [mulai_notif_ac] hari dari tanggal AC;
$config['mulai_tahun_psp']=2018; // mulai tahun psp akan di notifikasi;
$config['mulai_tahun_notifsipp']=2019; //mulai data sipp tahun akan dinotifikasi;