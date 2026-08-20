import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Standard A4 Landscape Dimensions
const CANVAS_W = 2380;
const CANVAS_H = 1460;

// Dynamic entity width calculation helper (snug fit, with custom overrides)
function getEntityDimensions(ent) {
  if (ent.id === 'kelas') {
    return { w: 140, h: 40 }; // Enlarged to fit 4 top relationship ports with cardinalities
  }
  const textLen = ent.name.length;
  const w = Math.max(84, Math.round(textLen * 9.6 + 18));
  const h = 40;
  return { w, h };
}

// 15 Entities in LOWERCASE
// - wakasis, sesi_konseling, and kepsek shifted left to X=1550
// - siswa shifted to X=1260, tindak_lanjut at X=1160
const rawEntities = [
  // --- ROW 1 (TOP, Y = 150) ---
  {
    id: 'admin',
    name: 'admin',
    cx: 360,
    cy: 150,
    attributes: [
      '*id_admin', 'username', 'password', 'nip', 'nama_lengkap', 'email',
      'no_hp', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'pendidikan_terakhir', 'foto_profil'
    ]
  },
  {
    id: 'tahun_ajaran',
    name: 'tahun_ajaran',
    cx: 860, // Exactly aligned vertically with kelas (X=860)
    cy: 150,
    customAttrSide: 'left',
    attributes: [
      '*id_tahun_ajaran',
      'nama_tahun_ajaran'
    ]
  },
  {
    id: 'jurusan',
    name: 'jurusan',
    cx: 1060,
    cy: 150,
    customAttrSide: 'right', // Attributes on RIGHT
    attributes: [
      '*id_jurusan',
      'nama_jurusan'
    ]
  },
  {
    id: 'wakasis',
    name: 'wakasis',
    cx: 1550, // Aligned with sesi_konseling and kepsek (X=1550)
    cy: 220, // Lowered to cy=220
    customAttrSide: 'right', // Attributes on RIGHT
    customTopY: 84, // Aligned with admin top attribute baseline at Y=84
    attributes: [
      '*id_wakasis', 'username', 'password', 'nip', 'nama_lengkap', 'email',
      'no_hp', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'jabatan', 'foto_profil'
    ]
  },

  // --- ROW 2 (MID-TOP, Y = 410) ---
  {
    id: 'wali_kelas',
    name: 'wali_kelas',
    cx: 360,
    cy: 410,
    attributes: [
      '*id_wali_kelas', 'username', 'password', 'nip_nuptk', 'nama_lengkap', 'email',
      'no_hp', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'jabatan', 'foto_profil'
    ]
  },
  {
    id: 'kelas',
    name: 'kelas',
    cx: 860,
    cy: 410,
    customAttrSide: 'left',
    attributes: [
      '*id_kelas', '**id_tahun_ajaran', 'nama_kelas',
      'tingkat_kelas', '**id_jurusan', '**id_wali_kelas'
    ]
  },
  {
    id: 'siswa',
    name: 'siswa',
    cx: 1260, // Shifted to X=1260 for ample clearance to X=1550 axis
    cy: 410,
    customAttrSide: 'right',
    customTopY: 150,
    attributes: [
      '*id_siswa', 'nis', 'nisn', 'nama_siswa', '**id_kelas', 'tahun_masuk', 'status_siswa', 'tempat_lahir',
      'tanggal_lahir', 'jenis_kelamin', 'agama', 'alamat', 'foto_siswa', 'no_wa_siswa', 'nama_orang_tua_wali', 'no_wa_orang_tua_wali'
    ]
  },

  // --- ROW 3 (MID, Y = 660) ---
  {
    id: 'guru_bk',
    name: 'guru_bk',
    cx: 360,
    cy: 660,
    attributes: [
      '*id_guru_bk', 'username', 'password', 'nip', 'nama_lengkap', 'email', 'no_hp', 'jenis_kelamin',
      'tempat_lahir', 'tanggal_lahir', 'alamat', 'jabatan', 'foto_profil', 'tanda_tangan_digital'
    ]
  },
  {
    id: 'jadwal_ketersediaan',
    name: 'jadwal_ketersediaan',
    cx: 860,
    cy: 660,
    customAttrSide: 'left',
    attributes: [
      '*id_jadwal', '**id_guru_bk', 'tanggal_tersedia',
      'jam_mulai', 'jam_selesai', 'status_slot'
    ]
  },
  {
    id: 'pengajuan_konseling',
    name: 'pengajuan_konseling',
    cx: 1230,
    cy: 660,
    customAttrSide: 'left',
    attributes: [
      '*id_pengajuan', '**id_siswa', '**id_jadwal', 'jenis_konseling', 'alasan_pengajuan', 'alasan_rujukan',
      'sumber_pengajuan', '**id_wali_kelas', 'status_pengajuan', 'tanggal_pengajuan', 'tanggal_pembatalan'
    ]
  },
  {
    id: 'sesi_konseling',
    name: 'sesi_konseling',
    cx: 1550, // Shifted to X=1550
    cy: 660,
    customAttrSide: 'right',
    customTopY: 460, // Raised cleanly above Y=660
    attributes: [
      '*id_sesi', '**id_pengajuan', 'status_sesi', 'tanggal_pelaksanaan',
      'status_kehadiran', 'hasil_konseling', 'rencana_tindak_lanjut'
    ]
  },

  // --- ROW 4 (BOTTOM, Y = 1050) ---
  {
    id: 'surat_panggilan',
    name: 'surat_panggilan',
    cx: 360,
    cy: 1050,
    customAttrSide: 'left',
    customTopY: 860, // Aligned with kepsek attributes at Y=860
    attributes: [
      '*id_surat', '**id_tindak_lanjut', '**id_guru_bk', 'nomor_surat',
      'perihal', 'isi_surat', 'tanggal_terbit', 'status_surat', 'status_kirim_wa'
    ]
  },
  {
    id: 'notifikasi',
    name: 'notifikasi',
    cx: 680,
    cy: 1050,
    customAttrSide: 'right',
    customTopY: 860, // Aligned with kepsek attributes at Y=860
    attributes: [
      '*id_notifikasi', 'judul_notifikasi', 'jenis_notifikasi', '**id_pengajuan', '**id_jadwal',
      '**id_surat', 'tipe_penerima', 'isi_pesan', 'no_wa_tujuan', 'status_kirim', 'tanggal_kirim'
    ]
  },
  {
    id: 'tindak_lanjut',
    name: 'tindak_lanjut',
    cx: 1160, // Positioned at X=1160
    cy: 1050,
    customAttrSide: 'left', // Attributes on LEFT
    customTopY: 980, // Lowered cleanly below Y=930 line (spans Y=980..1084)
    attributes: [
      '*id_tindak_lanjut', '**id_sesi',
      '**id_jadwal', 'jenis_aksi', 'status_tindak_lanjut'
    ]
  },
  {
    id: 'kepsek',
    name: 'kepsek',
    cx: 1550, // Shifted to X=1550 directly below sesi_konseling
    cy: 1050,
    customAttrSide: 'left', // Attributes on LEFT
    customTopY: 860, // Aligned with notifikasi at Y=860
    attributes: [
      '*id_kepsek', 'username', 'password', 'nip', 'nama_lengkap', 'email', 'no_hp',
      'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'jabatan', 'foto_profil', 'tanda_tangan_digital'
    ]
  }
];

// Attach dimensions to each entity
const entities = rawEntities.map(e => {
  const dims = getEntityDimensions(e);
  return { ...e, w: dims.w, h: dims.h };
});

// Layout attributes in clean vertical stacks
function layoutEntityAttributes(ent) {
  const attrs = ent.attributes;
  const total = attrs.length;
  const ovalSpacingY = total > 12 ? 22 : (total > 8 ? 24 : 26);
  const ovalRy = total > 12 ? 11.5 : (total > 8 ? 12 : 13);
  const gapX = 15;
  const items = [];

  // 1. Special case: Left-Only attributes
  if (ent.customAttrSide === 'left') {
    const totalH = (total - 1) * ovalSpacingY;
    const startY = ent.customTopY !== undefined ? ent.customTopY : (ent.cy - totalH / 2);

    attrs.forEach((attr, idx) => {
      const cy = startY + idx * ovalSpacingY;
      const textLen = attr.length;
      const rx = Math.max(48, textLen * 3.8 + 8);
      const cx = (ent.cx - ent.w / 2) - gapX - rx;
      const lineStartX = cx + rx;
      const lineStartY = cy;

      const entConnX = ent.cx - ent.w / 2;
      const t = total > 1 ? idx / (total - 1) : 0.5;
      const entConnY = (ent.cy - ent.h / 2 + 4) + t * (ent.h - 8);

      items.push({
        text: attr.toLowerCase(),
        cx,
        cy,
        rx,
        ry: ovalRy,
        isPK: attr.startsWith('*') && !attr.startsWith('**'),
        isFK: attr.startsWith('**'),
        line: {
          x1: lineStartX,
          y1: lineStartY,
          x2: entConnX,
          y2: entConnY
        }
      });
    });

    return items;
  }

  // 2. Special case: Right-Only attributes
  if (ent.customAttrSide === 'right') {
    const totalH = (total - 1) * ovalSpacingY;
    const startY = ent.customTopY !== undefined ? ent.customTopY : (ent.cy - totalH / 2);

    attrs.forEach((attr, idx) => {
      const cy = startY + idx * ovalSpacingY;
      const textLen = attr.length;
      const rx = Math.max(48, textLen * 3.8 + 8);
      const cx = (ent.cx + ent.w / 2) + gapX + rx;
      const lineStartX = cx - rx;
      const lineStartY = cy;

      const entConnX = ent.cx + ent.w / 2;
      const t = total > 1 ? idx / (total - 1) : 0.5;
      const entConnY = (ent.cy - ent.h / 2 + 4) + t * (ent.h - 8);

      items.push({
        text: attr.toLowerCase(),
        cx,
        cy,
        rx,
        ry: ovalRy,
        isPK: attr.startsWith('*') && !attr.startsWith('**'),
        isFK: attr.startsWith('**'),
        line: {
          x1: lineStartX,
          y1: lineStartY,
          x2: entConnX,
          y2: entConnY
        }
      });
    });

    return items;
  }

  // 3. Standard case: Left and Right vertical stacks
  const leftCount = Math.ceil(total / 2);
  const rightCount = total - leftCount;

  const leftAttrs = attrs.slice(0, leftCount);
  const rightAttrs = attrs.slice(leftCount);

  // Left stack
  const leftTotalH = (leftCount - 1) * ovalSpacingY;
  const leftStartY = ent.cy - leftTotalH / 2;

  leftAttrs.forEach((attr, idx) => {
    const cy = leftStartY + idx * ovalSpacingY;
    const textLen = attr.length;
    const rx = Math.max(48, textLen * 4.1 + 8);
    const cx = (ent.cx - ent.w / 2) - gapX - rx;
    const lineStartX = cx + rx;
    const lineStartY = cy;
    
    const entConnX = ent.cx - ent.w / 2;
    const t = leftCount > 1 ? idx / (leftCount - 1) : 0.5;
    const entConnY = (ent.cy - ent.h / 2 + 4) + t * (ent.h - 8);

    items.push({
      text: attr.toLowerCase(),
      cx,
      cy,
      rx,
      ry: ovalRy,
      isPK: attr.startsWith('*') && !attr.startsWith('**'),
      isFK: attr.startsWith('**'),
      line: {
        x1: lineStartX,
        y1: lineStartY,
        x2: entConnX,
        y2: entConnY
      }
    });
  });

  // Right stack
  const rightTotalH = (rightCount - 1) * ovalSpacingY;
  const rightStartY = ent.cy - rightTotalH / 2;

  rightAttrs.forEach((attr, idx) => {
    const cy = rightStartY + idx * ovalSpacingY;
    const textLen = attr.length;
    const rx = Math.max(48, textLen * 4.1 + 8);
    const cx = (ent.cx + ent.w / 2) + gapX + rx;
    const lineStartX = cx - rx;
    const lineStartY = cy;
    
    const entConnX = ent.cx + ent.w / 2;
    const t = rightCount > 1 ? idx / (rightCount - 1) : 0.5;
    const entConnY = (ent.cy - ent.h / 2 + 4) + t * (ent.h - 8);

    items.push({
      text: attr.toLowerCase(),
      cx,
      cy,
      rx,
      ry: ovalRy,
      isPK: attr.startsWith('*') && !attr.startsWith('**'),
      isFK: attr.startsWith('**'),
      line: {
        x1: lineStartX,
        y1: lineStartY,
        x2: entConnX,
        y2: entConnY
      }
    });
  });

  return items;
}

// 19 MULTI-TIER RELATIONSHIP PATHS
const relationships = [
  // --- CORRIDOR 1 (Between Row 1 [Y=150] and Row 2 [Y=410]) ---

  // 1. tahun_ajaran 1:N kelas (memiliki) - DIRECT STRAIGHT VERTICAL AT X = 860
  {
    from: 'tahun_ajaran',
    to: 'kelas',
    name: 'memiliki',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 860, cy: 220, w: 105, h: 36 },
    lineFrom: [
      { x: 860, y: 170 },
      { x: 860, y: 202 }
    ],
    cardFromPos: { x: 880, y: 190 },
    lineTo: [
      { x: 860, y: 238 },
      { x: 860, y: 390 }
    ],
    cardToPos: { x: 880, y: 365 }
  },

  // 2. jurusan 1:N kelas (memiliki) - Level Y = 250 -> Port X=910
  {
    from: 'jurusan',
    to: 'kelas',
    name: 'memiliki',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 985, cy: 250, w: 105, h: 36 },
    lineFrom: [
      { x: 1060, y: 170 },
      { x: 1060, y: 250 },
      { x: 1037.5, y: 250 }
    ],
    cardFromPos: { x: 1080, y: 195 },
    lineTo: [
      { x: 932.5, y: 250 },
      { x: 910, y: 250 },
      { x: 910, y: 390 }
    ],
    cardToPos: { x: 930, y: 365 }
  },

  // 3. admin 1:N kelas (mengelola) - Level Y = 255 -> Port X=835
  {
    from: 'admin',
    to: 'kelas',
    name: 'mengelola',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 597.5, cy: 255, w: 115, h: 36 },
    lineFrom: [
      { x: 360, y: 170 },
      { x: 360, y: 255 },
      { x: 540, y: 255 }
    ],
    cardFromPos: { x: 380, y: 195 },
    lineTo: [
      { x: 655, y: 255 },
      { x: 835, y: 255 },
      { x: 835, y: 390 }
    ],
    cardToPos: { x: 815, y: 365 }
  },

  // 4. wali_kelas 1:1 kelas (menjadi_wali) - Level Y = 315 -> Port X=805
  {
    from: 'wali_kelas',
    to: 'kelas',
    name: 'menjadi_wali',
    cardFrom: '1',
    cardTo: '1',
    diamond: { cx: 582.5, cy: 315, w: 125, h: 36 },
    lineFrom: [
      { x: 360, y: 390 },
      { x: 360, y: 315 },
      { x: 520, y: 315 }
    ],
    cardFromPos: { x: 380, y: 375 },
    lineTo: [
      { x: 645, y: 315 },
      { x: 805, y: 315 },
      { x: 805, y: 390 }
    ],
    cardToPos: { x: 785, y: 365 }
  },

  // 5. kelas 1:N siswa (memiliki) - DIRECT STRAIGHT HORIZONTAL AT Y = 410
  {
    from: 'kelas',
    to: 'siswa',
    name: 'memiliki',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 1077.5, cy: 410, w: 115, h: 36 },
    lineFrom: [
      { x: 930, y: 410 },
      { x: 1020, y: 410 }
    ],
    cardFromPos: { x: 955, y: 395 },
    lineTo: [
      { x: 1135, y: 410 },
      { x: 1225, y: 410 }
    ],
    cardToPos: { x: 1200, y: 395 }
  },


  // --- CORRIDOR 2 (Between Row 2 [Y=410] and Row 3 [Y=660]) ---

  // 6. siswa 1:N pengajuan_konseling (mengajukan) - Shifted right to X = 1290 (ZERO COLLISION WITH REL 9)
  {
    from: 'siswa',
    to: 'pengajuan_konseling',
    name: 'mengajukan',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 1290, cy: 540, w: 115, h: 36 },
    lineFrom: [
      { x: 1290, y: 430 },
      { x: 1290, y: 522 }
    ],
    cardFromPos: { x: 1310, y: 465 },
    lineTo: [
      { x: 1290, y: 558 },
      { x: 1290, y: 640 } // pengajuan top port 3 at Y=640
    ],
    cardToPos: { x: 1310, y: 605 }
  },

  // 7. wali_kelas 1:N pengajuan_konseling (merujuk) - Level Y = 520 -> Port 1 (X=1175)
  {
    from: 'wali_kelas',
    to: 'pengajuan_konseling',
    name: 'merujuk',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 740, cy: 520, w: 115, h: 36 },
    lineFrom: [
      { x: 360, y: 430 },
      { x: 360, y: 520 },
      { x: 682.5, y: 520 }
    ],
    cardFromPos: { x: 380, y: 465 },
    lineTo: [
      { x: 797.5, y: 520 },
      { x: 1175, y: 520 },
      { x: 1175, y: 640 } // pengajuan top port 1 at Y=640 (leftmost)
    ],
    cardToPos: { x: 1155, y: 605 }
  },

  // 8. guru_bk 1:N jadwal_ketersediaan (membuat) - Level Y = 575
  {
    from: 'guru_bk',
    to: 'jadwal_ketersediaan',
    name: 'membuat',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 605, cy: 575, w: 115, h: 36 },
    lineFrom: [
      { x: 360, y: 640 },
      { x: 360, y: 575 },
      { x: 547.5, y: 575 }
    ],
    cardFromPos: { x: 380, y: 615 },
    lineTo: [
      { x: 662.5, y: 575 },
      { x: 835, y: 575 },
      { x: 835, y: 640 } // jadwal top port at Y=640
    ],
    cardToPos: { x: 855, y: 615 }
  },

  // 9. jadwal_ketersediaan 1:N pengajuan_konseling (digunakan) - Level Y = 480 -> Port 2 (X=1215)
  {
    from: 'jadwal_ketersediaan',
    to: 'pengajuan_konseling',
    name: 'digunakan',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 970, cy: 480, w: 125, h: 36 },
    lineFrom: [
      { x: 885, y: 640 },
      { x: 885, y: 480 },
      { x: 907.5, y: 480 }
    ],
    cardFromPos: { x: 905, y: 605 },
    lineTo: [
      { x: 1032.5, y: 480 },
      { x: 1215, y: 480 },
      { x: 1215, y: 640 } // pengajuan top port 2 at Y=640 (middle)
    ],
    cardToPos: { x: 1235, y: 605 }
  },

  // 10. pengajuan_konseling 1:1 sesi_konseling (menghasilkan) - DIRECT STRAIGHT HORIZONTAL AT Y = 660
  {
    from: 'pengajuan_konseling',
    to: 'sesi_konseling',
    name: 'menghasilkan',
    cardFrom: '1',
    cardTo: '1',
    diamond: { cx: 1397.5, cy: 660, w: 115, h: 36 },
    lineFrom: [
      { x: 1320, y: 660 },
      { x: 1340, y: 660 }
    ],
    cardFromPos: { x: 1335, y: 645 },
    lineTo: [
      { x: 1455, y: 660 },
      { x: 1475, y: 660 } // sesi_konseling left border (cx=1550, w=150)
    ],
    cardToPos: { x: 1460, y: 645 }
  },


  // --- CORRIDOR 3 (Between Row 3 [Y=660] and Row 4 [Y=1050]) ---

  // 11. guru_bk 1:N surat_panggilan (membuat) - DIRECT STRAIGHT VERTICAL AT X = 360
  {
    from: 'guru_bk',
    to: 'surat_panggilan',
    name: 'membuat',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 360, cy: 855, w: 115, h: 36 },
    lineFrom: [
      { x: 360, y: 680 },
      { x: 360, y: 837 }
    ],
    cardFromPos: { x: 380, y: 740 },
    lineTo: [
      { x: 360, y: 873 },
      { x: 360, y: 1030 }
    ],
    cardToPos: { x: 380, y: 990 }
  },

  // 12. pengajuan_konseling 1:N notifikasi (memicu) - Centered at cx = 1025 (ZERO COLLISION WITH X=900 DROP)
  {
    from: 'pengajuan_konseling',
    to: 'notifikasi',
    name: 'memicu',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 1025, cy: 830, w: 115, h: 36 },
    lineFrom: [
      { x: 1150, y: 680 },
      { x: 1150, y: 830 },
      { x: 1082.5, y: 830 }
    ],
    cardFromPos: { x: 1170, y: 720 },
    lineTo: [
      { x: 967.5, y: 830 },
      { x: 700, y: 830 },
      { x: 700, y: 1030 }
    ],
    cardToPos: { x: 720, y: 990 }
  },

  // 13. sesi_konseling 1:N tindak_lanjut (memiliki) - Level Y = 740 -> Port 2 (X=1200)
  {
    from: 'sesi_konseling',
    to: 'tindak_lanjut',
    name: 'memiliki',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 1350, cy: 740, w: 115, h: 36 },
    lineFrom: [
      { x: 1500, y: 680 },
      { x: 1500, y: 740 },
      { x: 1407.5, y: 740 }
    ],
    cardFromPos: { x: 1520, y: 710 },
    lineTo: [
      { x: 1292.5, y: 740 },
      { x: 1200, y: 740 },
      { x: 1200, y: 1030 } // tindak_lanjut top right port at Y=1030
    ],
    cardToPos: { x: 1220, y: 990 }
  },

  // 14. jadwal_ketersediaan 1:N notifikasi (memicu) - Lowered to Level Y = 770
  {
    from: 'jadwal_ketersediaan',
    to: 'notifikasi',
    name: 'memicu',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 750, cy: 770, w: 115, h: 36 },
    lineFrom: [
      { x: 840, y: 680 },
      { x: 840, y: 770 },
      { x: 807.5, y: 770 }
    ],
    cardFromPos: { x: 860, y: 730 },
    lineTo: [
      { x: 692.5, y: 770 },
      { x: 660, y: 770 },
      { x: 660, y: 1030 }
    ],
    cardToPos: { x: 640, y: 990 }
  },

  // 15. jadwal_ketersediaan 1:N tindak_lanjut (digunakan) - Level Y = 930 -> Port 1 (X=1130, from left)
  {
    from: 'jadwal_ketersediaan',
    to: 'tindak_lanjut',
    name: 'digunakan',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 1015, cy: 930, w: 125, h: 36 },
    lineFrom: [
      { x: 900, y: 680 },
      { x: 900, y: 930 },
      { x: 952.5, y: 930 }
    ],
    cardFromPos: { x: 920, y: 730 },
    lineTo: [
      { x: 1077.5, y: 930 },
      { x: 1130, y: 930 },
      { x: 1130, y: 1030 } // tindak_lanjut top left port at Y=1030
    ],
    cardToPos: { x: 1110, y: 990 }
  },


  // --- CORRIDOR 4 (Row 4 Direct Horizontal AT Y = 1050) ---

  // 16. surat_panggilan 1:N notifikasi (memicu) - DIRECT STRAIGHT HORIZONTAL AT Y = 1050
  {
    from: 'surat_panggilan',
    to: 'notifikasi',
    name: 'memicu',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 532.5, cy: 1050, w: 105, h: 36 },
    lineFrom: [
      { x: 430, y: 1050 },
      { x: 480, y: 1050 }
    ],
    cardFromPos: { x: 455, y: 1035 },
    lineTo: [
      { x: 585, y: 1050 },
      { x: 635, y: 1050 }
    ],
    cardToPos: { x: 610, y: 1035 }
  },

  // 17. tindak_lanjut 1:N surat_panggilan (menghasilkan) - Level Y = 1140
  {
    from: 'tindak_lanjut',
    to: 'surat_panggilan',
    name: 'menghasilkan',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 760, cy: 1140, w: 135, h: 36 },
    lineFrom: [
      { x: 1160, y: 1070 },
      { x: 1160, y: 1140 },
      { x: 827.5, y: 1140 }
    ],
    cardFromPos: { x: 1180, y: 1105 },
    lineTo: [
      { x: 692.5, y: 1140 },
      { x: 360, y: 1140 },
      { x: 360, y: 1070 }
    ],
    cardToPos: { x: 380, y: 1105 }
  },


  // --- MONITORING RELATIONS FOR WAKASIS & KEPSEK ---

  // 18. wakasis 1:N sesi_konseling (memantau) - DIRECT VERTICAL AT X = 1550 (LOWERED TO CY = 510)
  {
    from: 'wakasis',
    to: 'sesi_konseling',
    name: 'memantau',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 1550, cy: 510, w: 115, h: 36 },
    lineFrom: [
      { x: 1550, y: 240 },
      { x: 1550, y: 492 }
    ],
    cardFromPos: { x: 1570, y: 270 },
    lineTo: [
      { x: 1550, y: 528 },
      { x: 1550, y: 640 } // sesi top center port at Y=640
    ],
    cardToPos: { x: 1570, y: 610 }
  },

  // 19. kepsek 1:N sesi_konseling (memantau) - DIRECT VERTICAL AT X = 1550 (RAISED TO CY = 790)
  {
    from: 'kepsek',
    to: 'sesi_konseling',
    name: 'memantau',
    cardFrom: '1',
    cardTo: 'N',
    diamond: { cx: 1550, cy: 790, w: 115, h: 36 },
    lineFrom: [
      { x: 1550, y: 1030 },
      { x: 1550, y: 808 }
    ],
    cardFromPos: { x: 1570, y: 990 },
    lineTo: [
      { x: 1550, y: 772 },
      { x: 1550, y: 680 } // sesi bottom center port at Y=680
    ],
    cardToPos: { x: 1570, y: 710 }
  }
];

function generateSvg() {
  let svg = `<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${CANVAS_W} ${CANVAS_H}" width="${CANVAS_W}" height="${CANVAS_H}">
  <defs>
    <style>
      .bg { fill: #ffffff; }
      .entity-rect { fill: #ffffff; stroke: #000000; stroke-width: 1.2; }
      .entity-text { font-family: 'Arial', 'Helvetica', sans-serif; font-size: 16px; font-weight: normal; fill: #000000; text-anchor: middle; dominant-baseline: central; letter-spacing: 0.3px; }
      .attr-oval { fill: #ffffff; stroke: #000000; stroke-width: 1.0; }
      .attr-oval-pk { fill: #ffffff; stroke: #000000; stroke-width: 1.3; }
      .attr-oval-fk { fill: #ffffff; stroke: #000000; stroke-width: 1.1; }
      .attr-line { stroke: #000000; stroke-width: 1.0; }
      .attr-text { font-family: 'Arial', 'Helvetica', sans-serif; font-size: 12.5px; font-weight: normal; fill: #000000; text-anchor: middle; dominant-baseline: central; }
      .rel-line { stroke: #000000; stroke-width: 1.1; fill: none; stroke-linecap: round; stroke-linejoin: round; }
      .diamond { fill: #ffffff; stroke: #000000; stroke-width: 1.2; }
      .rel-text { font-family: 'Arial', 'Helvetica', sans-serif; font-size: 13px; font-weight: normal; fill: #000000; text-anchor: middle; dominant-baseline: central; }
      .card-text { font-family: 'Arial', 'Helvetica', sans-serif; font-size: 16.5px; font-weight: normal; fill: #000000; text-anchor: middle; dominant-baseline: central; }
    </style>
  </defs>

  <!-- Page Background (Pure Diagram, Clean) -->
  <rect width="${CANVAS_W}" height="${CANVAS_H}" class="bg"/>
`;

  // 1. Draw Attribute Lines First
  svg += `\n  <!-- ATTRIBUTE CONNECTING LINES -->\n  <g id="attr-lines">\n`;
  entities.forEach(ent => {
    const attrs = layoutEntityAttributes(ent);
    attrs.forEach(a => {
      svg += `    <line x1="${a.line.x1}" y1="${a.line.y1}" x2="${a.line.x2}" y2="${a.line.y2}" class="attr-line"/>\n`;
    });
  });
  svg += `  </g>\n`;

  // 2. Draw Relationship Lines
  svg += `\n  <!-- RELATIONSHIP LINES -->\n  <g id="rel-lines">\n`;
  relationships.forEach(rel => {
    const ptsFrom = rel.lineFrom.map(p => `${p.x},${p.y}`).join(' ');
    const ptsTo = rel.lineTo.map(p => `${p.x},${p.y}`).join(' ');
    svg += `    <polyline points="${ptsFrom}" class="rel-line"/>\n`;
    svg += `    <polyline points="${ptsTo}" class="rel-line"/>\n`;
    svg += `    <text x="${rel.cardFromPos.x}" y="${rel.cardFromPos.y}" class="card-text">${rel.cardFrom}</text>\n`;
    svg += `    <text x="${rel.cardToPos.x}" y="${rel.cardToPos.y}" class="card-text">${rel.cardTo}</text>\n`;
  });
  svg += `  </g>\n`;

  // 3. Draw Diamonds
  svg += `\n  <!-- RELATIONSHIP DIAMONDS -->\n  <g id="rel-diamonds">\n`;
  relationships.forEach(rel => {
    const d = rel.diamond;
    const pts = [
      `${d.cx},${d.cy - d.h / 2}`,
      `${d.cx + d.w / 2},${d.cy}`,
      `${d.cx},${d.cy + d.h / 2}`,
      `${d.cx - d.w / 2},${d.cy}`
    ].join(' ');
    svg += `    <g>\n`;
    svg += `      <polygon points="${pts}" class="diamond"/>\n`;
    svg += `      <text x="${d.cx}" y="${d.cy}" class="rel-text">${rel.name}</text>\n`;
    svg += `    </g>\n`;
  });
  svg += `  </g>\n`;

  // 4. Draw Attribute Ovals
  svg += `\n  <!-- ATTRIBUTE OVALS -->\n  <g id="attr-ovals">\n`;
  entities.forEach(ent => {
    const attrs = layoutEntityAttributes(ent);
    attrs.forEach(a => {
      let ovalCls = 'attr-oval';
      if (a.isPK) {
        ovalCls = 'attr-oval-pk';
      } else if (a.isFK) {
        ovalCls = 'attr-oval-fk';
      }

      svg += `    <g>\n`;
      svg += `      <ellipse cx="${a.cx}" cy="${a.cy}" rx="${a.rx}" ry="${a.ry}" class="${ovalCls}"/>\n`;
      svg += `      <text x="${a.cx}" y="${a.cy}" class="attr-text">${a.text}</text>\n`;
    });
  });
  svg += `  </g>\n`;

  // 5. Draw Entities (Snug Fit)
  svg += `\n  <!-- ENTITIES -->\n  <g id="entities">\n`;
  entities.forEach(ent => {
    const x = ent.cx - ent.w / 2;
    const y = ent.cy - ent.h / 2;
    svg += `    <g id="entity-${ent.id}">\n`;
    svg += `      <rect x="${x}" y="${y}" width="${ent.w}" height="${ent.h}" class="entity-rect"/>\n`;
    svg += `      <text x="${ent.cx}" y="${ent.cy}" class="entity-text">${ent.name}</text>\n`;
    svg += `    </g>\n`;
  });
  svg += `  </g>\n`;

  svg += `\n</svg>`;
  return svg;
}

const svgContent = generateSvg();
const svgFilePath = path.join(__dirname, 'erd_konseling_final.svg');
fs.writeFileSync(svgFilePath, svgContent, 'utf8');

// Update Interactive HTML Viewer
const htmlContent = `<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ERD Final - wakasis, sesi_konseling, kepsek Digeser ke X=1550 Sempurna</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: #0f172a;
      color: #f8fafc;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      height: 100vh;
      width: 100vw;
    }
    header {
      background: #1e293b;
      border-bottom: 1px solid #334155;
      padding: 12px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      z-index: 100;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .header-title h1 {
      font-size: 18px;
      font-weight: 700;
      color: #ffffff;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .badge {
      background: #10b981;
      color: #ffffff;
      font-size: 11px;
      padding: 2px 8px;
      border-radius: 999px;
      font-weight: 600;
    }
    .header-title p {
      font-size: 12px;
      color: #94a3b8;
      margin-top: 2px;
    }
    .controls {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .btn {
      background: #334155;
      color: #f8fafc;
      border: 1px solid #475569;
      padding: 7px 14px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s ease;
    }
    .btn:hover { background: #475569; border-color: #64748b; }
    .btn-primary { background: #2563eb; border-color: #3b82f6; }
    .btn-primary:hover { background: #1d4ed8; }
    .btn-success { background: #059669; border-color: #10b981; }
    .btn-success:hover { background: #047857; }
    .zoom-level {
      font-family: 'JetBrains Mono', monospace;
      font-size: 13px;
      min-width: 60px;
      text-align: center;
      color: #cbd5e1;
    }
    #viewport-container {
      flex: 1;
      position: relative;
      overflow: hidden;
      background: #94a3b8;
      cursor: grab;
    }
    #viewport-container:active { cursor: grabbing; }
    #svg-wrapper {
      position: absolute;
      transform-origin: 0 0;
      background: #ffffff;
      box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }
    #erd-svg {
      display: block;
      width: ${CANVAS_W}px;
      height: ${CANVAS_H}px;
    }
    .info-panel {
      position: absolute;
      bottom: 20px;
      right: 20px;
      background: rgba(15, 23, 42, 0.9);
      backdrop-filter: blur(8px);
      border: 1px solid #334155;
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 12px;
      color: #94a3b8;
      max-width: 340px;
      pointer-events: none;
      z-index: 50;
    }
    .info-panel strong { color: #f1f5f9; }

    @media print {
      @page {
        size: A4 landscape;
        margin: 5mm;
      }
      body {
        background: #ffffff !important;
        overflow: visible !important;
        height: auto !important;
        width: auto !important;
      }
      header, .info-panel, .controls {
        display: none !important;
      }
      #viewport-container {
        background: #ffffff !important;
        position: static !important;
        overflow: visible !important;
        width: 100% !important;
        height: 100% !important;
      }
      #svg-wrapper {
        position: static !important;
        transform: none !important;
        box-shadow: none !important;
        width: 100% !important;
        height: 100% !important;
      }
      svg {
        width: 100% !important;
        height: auto !important;
        max-height: 100vh !important;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="header-title">
      <h1>ERD Final: Sistem Informasi Konseling Siswa <span class="badge">Posisi Sempurna</span></h1>
      <p>wakasis, sesi_konseling, kepsek Digeser ke X=1550 • 1 Halaman A4</p>
    </div>
    <div class="controls">
      <button class="btn" id="btn-zoom-out" title="Zoom Out">🔍 -</button>
      <span class="zoom-level" id="zoom-text">100%</span>
      <button class="btn" id="btn-zoom-in" title="Zoom In">🔍 +</button>
      <button class="btn" id="btn-reset" title="Fit to Screen">🔄 Reset View</button>
      <button class="btn btn-primary" id="btn-print" title="Print / PDF">🖨️ Cetak / PDF (A4)</button>
      <button class="btn btn-primary" id="btn-download-svg">⬇ Download SVG</button>
      <button class="btn btn-success" id="btn-download-png">📷 Export High-Res PNG</button>
      <button class="btn btn-success" id="btn-download-jpg">📷 Export High-Res JPG</button>
    </div>
  </header>

  <div id="viewport-container">
    <div id="svg-wrapper">
      ${svgContent}
    </div>
    <div class="info-panel">
      <strong>💡 Penyempurnaan Terkini:</strong><br>
      • <b>wakasis, sesi_konseling, kepsek</b>: Digeser ke X=1550.<br>
      • <b>wakasis ➔ sesi ➔ kepsek</b>: Sumbu simetris vertikal lurus murni 100% pada X=1550.<br>
      • <b>100% Bebas Tumpang Tindih</b>.
    </div>
  </div>

  <script>
    const container = document.getElementById('viewport-container');
    const wrapper = document.getElementById('svg-wrapper');
    const zoomText = document.getElementById('zoom-text');
    const btnZoomIn = document.getElementById('btn-zoom-in');
    const btnZoomOut = document.getElementById('btn-zoom-out');
    const btnReset = document.getElementById('btn-reset');
    const btnPrint = document.getElementById('btn-print');
    const btnDownloadSvg = document.getElementById('btn-download-svg');
    const btnDownloadPng = document.getElementById('btn-download-png');
    const btnDownloadJpg = document.getElementById('btn-download-jpg');

    let scale = 0.5;
    let posX = 20;
    let posY = 20;
    let isDragging = false;
    let startX = 0;
    let startY = 0;

    function updateTransform() {
      wrapper.style.transform = \`translate(\${posX}px, \${posY}px) scale(\${scale})\`;
      zoomText.textContent = Math.round(scale * 100) + '%';
    }

    function fitToScreen() {
      const cw = container.clientWidth;
      const ch = container.clientHeight;
      const svgW = ${CANVAS_W};
      const svgH = ${CANVAS_H};
      scale = Math.min((cw - 40) / svgW, (ch - 40) / svgH);
      posX = Math.max(10, (cw - svgW * scale) / 2);
      posY = Math.max(10, (ch - svgH * scale) / 2);
      updateTransform();
    }

    window.addEventListener('resize', fitToScreen);
    setTimeout(fitToScreen, 100);

    btnZoomIn.addEventListener('click', () => {
      scale = Math.min(scale * 1.25, 3.0);
      updateTransform();
    });

    btnZoomOut.addEventListener('click', () => {
      scale = Math.max(scale / 1.25, 0.1);
      updateTransform();
    });

    btnReset.addEventListener('click', fitToScreen);
    btnPrint.addEventListener('click', () => window.print());

    container.addEventListener('wheel', (e) => {
      e.preventDefault();
      const zoomFactor = e.deltaY < 0 ? 1.15 : 0.87;
      const rect = container.getBoundingClientRect();
      const mouseX = e.clientX - rect.left;
      const mouseY = e.clientY - rect.top;

      const newScale = Math.min(Math.max(scale * zoomFactor, 0.1), 3.5);
      posX = mouseX - (mouseX - posX) * (newScale / scale);
      posY = mouseY - (mouseY - posY) * (newScale / scale);
      scale = newScale;
      updateTransform();
    }, { passive: false });

    container.addEventListener('mousedown', (e) => {
      isDragging = true;
      startX = e.clientX - posX;
      startY = e.clientY - posY;
    });

    window.addEventListener('mousemove', (e) => {
      if (!isDragging) return;
      posX = e.clientX - startX;
      posY = e.clientY - startY;
      updateTransform();
    });

    window.addEventListener('mouseup', () => {
      isDragging = false;
    });

    btnDownloadSvg.addEventListener('click', () => {
      const svgElem = document.querySelector('#svg-wrapper svg');
      const serializer = new XMLSerializer();
      let source = serializer.serializeToString(svgElem);
      if(!source.match(/^<svg[^>]+xmlns="http\\:\\/\\/www\\.w3\\.org\\/2000\\/svg"/)){
        source = source.replace(/^<svg/, '<svg xmlns="http://www.w3.org/2000/svg"');
      }
      const blob = new Blob([source], { type: "image/svg+xml;charset=utf-8" });
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url;
      a.download = "ERD_FINAL_Wakasis_Sesi_Kepsek_Shifted_X1550.svg";
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      URL.revokeObjectURL(url);
    });

    btnDownloadPng.addEventListener('click', () => {
      btnDownloadPng.textContent = '⏳ Rendering PNG...';
      btnDownloadPng.disabled = true;

      const svgElem = document.querySelector('#svg-wrapper svg');
      const serializer = new XMLSerializer();
      const source = serializer.serializeToString(svgElem);
      const svgBlob = new Blob([source], { type: "image/svg+xml;charset=utf-8" });
      const url = URL.createObjectURL(svgBlob);

      const img = new Image();
      img.onload = () => {
        const canvas = document.createElement('canvas');
        const exportScale = 3.0;
        canvas.width = ${CANVAS_W} * exportScale;
        canvas.height = ${CANVAS_H} * exportScale;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

        canvas.toBlob((blob) => {
          const pngUrl = URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = pngUrl;
          a.download = 'ERD.png';
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
          URL.revokeObjectURL(pngUrl);
          URL.revokeObjectURL(url);

          btnDownloadPng.textContent = '📷 Export High-Res PNG';
          btnDownloadPng.disabled = false;
        }, 'image/png');
      };
      img.src = url;
    });

    btnDownloadJpg.addEventListener('click', () => {
      btnDownloadJpg.textContent = '⏳ Rendering JPG...';
      btnDownloadJpg.disabled = true;

      const svgElem = document.querySelector('#svg-wrapper svg');
      const serializer = new XMLSerializer();
      const source = serializer.serializeToString(svgElem);
      const svgBlob = new Blob([source], { type: "image/svg+xml;charset=utf-8" });
      const url = URL.createObjectURL(svgBlob);

      const img = new Image();
      img.onload = () => {
        const canvas = document.createElement('canvas');
        const exportScale = 3.0;
        canvas.width = ${CANVAS_W} * exportScale;
        canvas.height = ${CANVAS_H} * exportScale;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

        canvas.toBlob((blob) => {
          const jpgUrl = URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = jpgUrl;
          a.download = 'ERD.jpg';
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
          URL.revokeObjectURL(jpgUrl);
          URL.revokeObjectURL(url);

          btnDownloadJpg.textContent = '📷 Export High-Res JPG';
          btnDownloadJpg.disabled = false;
        }, 'image/jpeg', 0.95);
      };
      img.src = url;
    });
  </script>
</body>
</html>
`;

const htmlFilePath = path.join(__dirname, 'erd_konseling_final.html');
fs.writeFileSync(htmlFilePath, htmlContent, 'utf8');
console.log('Regenerated wakasis, sesi_konseling, and kepsek shifted to X=1550 ERD successfully.');
