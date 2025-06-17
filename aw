import { useState } from "react";

interface Jasa {
  id: number;
  nama: string;
  harga: number;
  estimasi: string;
  kategori: "coding" | "mtk";
}

interface ItemKeranjang extends Jasa {
  jumlah: number;
  tingkat?: string; // untuk MTK
}

const jasaList: Jasa[] = [
  { id: 1, nama: "Joki Coding Dasar", harga: 75000, estimasi: "1-2 hari", kategori: "coding" },
  { id: 2, nama: "Joki Coding Website", harga: 150000, estimasi: "2-4 hari", kategori: "coding" },
  { id: 3, nama: "Joki Coding Mobile App", harga: 225000, estimasi: "3-5 hari", kategori: "coding" },
  { id: 4, nama: "Joki MTK SMP", harga: 20000, estimasi: "1 hari", kategori: "mtk" },
  { id: 5, nama: "Joki MTK SMA", harga: 30000, estimasi: "1-2 hari", kategori: "mtk" },
];

const testimonis = [
  { nama: "Andi", isi: "Tugas selesai tepat waktu, mantap!" },
  { nama: "Sari", isi: "MTK kelas 11 jadi gampang berkat jasa ini." },
  { nama: "Budi", isi: "Dibimbing juga cara kerjanya. Recommended!" },
];

export default function App() {
  const [keranjang, setKeranjang] = useState<ItemKeranjang[]>([]);
  const [tingkat, setTingkat] = useState<string>("SMP");

  const tambah = (jasa: Jasa) => {
    const newItem: ItemKeranjang = jasa.kategori === "mtk"
      ? { ...jasa, jumlah: 1, tingkat }
      : { ...jasa, jumlah: 1 };

    setKeranjang((prev) => {
      const ada = prev.find((item) => item.id === jasa.id && item.tingkat === tingkat);
      if (ada) {
        return prev.map((item) =>
          item.id === jasa.id && item.tingkat === tingkat
            ? { ...item, jumlah: item.jumlah + 1 }
            : item
        );
      }
      return [...prev, newItem];
    });
  };

  const kurang = (item: ItemKeranjang) => {
    setKeranjang((prev) =>
      prev
        .map((i) =>
          i.id === item.id && i.tingkat === item.tingkat
            ? { ...i, jumlah: i.jumlah - 1 }
            : i
        )
        .filter((i) => i.jumlah > 0)
    );
  };

  const totalHarga = keranjang.reduce((total, item) => total + item.harga * item.jumlah, 0);
  const diskon = totalHarga >= 100000 ? totalHarga * 0.1 : 0;
  const totalSetelahDiskon = totalHarga - diskon;

  const checkout = () => {
    const isiPesan = keranjang
      .map(
        (item) =>
          `${item.nama} ${item.kategori === "mtk" ? `(${item.tingkat})` : ""} - ${item.jumlah}x = Rp${item.harga * item.jumlah}`
      )
      .join("%0A");
    const link = `https://wa.me/62xxxxxxxxxx?text=Pesan:%0A${isiPesan}%0ATotal:%20Rp${totalHarga}%0ADiskon:%20Rp${diskon}%0A*Total Bayar:*%20Rp${totalSetelahDiskon}`;
    window.open(link, "_blank");
  };

  return (
    <div style={{ maxWidth: 500, margin: "0 auto", padding: 16, fontFamily: "sans-serif" }}>
      <h1 style={{ textAlign: "center" }}>Jasa Joki Tugas Coding & MTK</h1>

      <h3>Pilih Tingkat (untuk MTK):</h3>
      <select value={tingkat} onChange={(e) => setTingkat(e.target.value)}>
        <option value="SMP">SMP</option>
        <option value="SMA Kelas 10">SMA Kelas 10</option>
        <option value="SMA Kelas 11">SMA Kelas 11</option>
        <option value="SMA Kelas 12">SMA Kelas 12</option>
      </select>

      <h3 style={{ marginTop: 16 }}>Daftar Jasa:</h3>
      {jasaList.map((jasa) => (
        <div
          key={jasa.id}
          style={{
            border: "1px solid #ccc",
            borderRadius: 6,
            padding: 8,
            marginBottom: 10,
          }}
        >
          <strong>{jasa.nama}</strong> <br />
          <small>Rp{jasa.harga} • Estimasi: {jasa.estimasi}</small> <br />
          <button
            onClick={() => tambah(jasa)}
            style={{
              marginTop: 5,
              backgroundColor: "#28a745",
              color: "#fff",
              border: "none",
              padding: "5px 10px",
              borderRadius: 4,
              cursor: "pointer",
            }}
          >
            Tambah ke Keranjang
          </button>
        </div>
      ))}

      <h3>Keranjang:</h3>
      {keranjang.length === 0 ? (
        <p>Belum ada jasa dipilih</p>
      ) : (
        <ul style={{ listStyle: "none", padding: 0 }}>
          {keranjang.map((item, idx) => (
            <li key={idx} style={{ marginBottom: 6 }}>
              {item.nama} {item.kategori === "mtk" ? `(${item.tingkat})` : ""} - {item.jumlah}x
              <br />
              <small>Rp{item.harga * item.jumlah}</small>
              <br />
              <button onClick={() => kurang(item)} style={{ marginRight: 5 }}>−</button>
              <button onClick={() => tambah(item)}>+</button>
            </li>
          ))}
        </ul>
      )}

      <p>Total: Rp{totalHarga}</p>
      {diskon > 0 && <p>Diskon 10%: -Rp{diskon}</p>}
      <p><strong>Total Bayar: Rp{totalSetelahDiskon}</strong></p>

      <button
        onClick={checkout}
        disabled={keranjang.length === 0}
        style={{
          backgroundColor: keranjang.length === 0 ? "#ccc" : "#007bff",
          color: "#fff",
          padding: 8,
          borderRadius: 4,
          width: "100%",
          marginTop: 8,
        }}
      >
        Checkout via WhatsApp
      </button>

      <h3 style={{ marginTop: 20 }}>Testimoni Pelanggan:</h3>
      {testimonis.map((t, idx) => (
        <blockquote key={idx} style={{ fontStyle: "italic", borderLeft: "3px solid #ccc", marginBottom: 8, paddingLeft: 8 }}>
          “{t.isi}” <br /> <small>— {t.nama}</small>
        </blockquote>
      ))}

      <h3>Kontak & Sosmed:</h3>
      <ul>
        <li>WhatsApp: <a href="https://wa.me/62xxxxxxxxxx" target="_blank">Chat Admin</a></li>
        <li>Instagram: <a href="https://instagram.com/akun_instagram" target="_blank">@akun_instagram</a></li>
        <li>Tiktok: <a href="https://tiktok.com/@akun_tiktok" target="_blank">@akun_tiktok</a></li>
      </ul>
    </div>
  );
}
