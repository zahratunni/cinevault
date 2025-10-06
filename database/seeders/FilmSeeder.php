<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Film;

class FilmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

         Film::create([
            'judul' => 'Andai Ibu Tidak Menikah Dengan Ayah', 
            'sinopsis' => 'Saat beasiswa kuliah kedokterannya terancam dicabut, Alin (Amanda Rawles) yang merantau terpaksa kembali ke rumah. Setelah kembali ke rumah, ia kemudian menyadari bahwa kehidupan keluarganya kini makin susah, sementara Ayahnya (Bucek) jarang ada di rumah. Adik (Nayla Purnama)dan Kakaknya (Eva Celia) juga harus menanggung banyak beban di keluarga hingga mengorbankan diri dan mimpi-mimpi mereka. Alin juga tanpa sengaja menemukan buku harian milik ibunya. Isi buku harian tersebut penuh dengan memori masa muda ibunya, termasuk mimpi-mimpinya. Ini membuat Alin bertanya-tanya, andai ibu tidak menikah dengan ayah, akankah hidup ibunya lebih bahagia? Pertanyaan itu pun membuat Alin berpikir apakah Irfan (Indian Akbar), pasangannya, adalah pasangan yang tepat untuk dirinya?', 
            'durasi_menit' => 119, 
            'rating' => '13+', 
            'genre' => 'Drama', 
            'status_tayang' => 'Playing Now', 
            'poster_url' => '1.jpg', 
            'trailer_url' => 'https://youtu.be/IfkNOBDzFVM?si=iNJARY2QFKLZOfGx', 
            'produser' => 'Gope T. Samtani',
            'sutradara' => 'Kuntz Agus',
            'penulis' => 'Evelyn Afnilia',
            'produksi' => 'Rapi Films, Screenplay Films, Legacy Pictures, Vor',
            'cast_list' => 'Amanda Rawles, Sha Ine Febriyanti, Bucek, Eva Celia, Nayla Purnama, Indian Akbar',
        ]);

         Film::create([
            'judul' => 'Sukma', 
            'sinopsis' => 'Kepindahan Arini (Luna Maya) dan keluarganya ke kota kecil untuk memulai hidup baru, justru berbalik menjadi petaka setelah mereka menemukan sebuah cermin kuno di ruang rahasia. Serangkaian keanehan terjadi. Suara dan penampakan yang tidak diduga membuat Arini cemas akan keselamatan dirinya dan keluarganya. Ditambah lagi munculnya sosok misterius Ibu Sri (Christine Hakim). Waktu terus berjalan, dan Arini harus mengungkap masa lalu dan misteri cermin tersebut - sebelum semuanya terlambat dan Arini kehilangan semuanya.', 
            'durasi_menit' => 108, 
            'rating' => '13+', 
            'genre' => 'Horror', 
            'status_tayang' => 'Playing Now', 
            'poster_url' => '2.jpg', 
            'trailer_url' => 'https://youtu.be/LV1kbyfHUN8?si=pmDpv0VFxa1xsnLT', 
            'produser' => 'David Wong, Baim Wong',
            'sutradara' => 'Baim Wong',
            'penulis' => 'Ratih Kumala, Baim Wong',
            'produksi' => 'Tiger Wong Entertainment',
            'cast_list' => 'Luna Maya, Christine Hakim, Fedi Nuril, Oka Antara, Anna Jobling, Kimberly Ryder, Asri Welas, Krishna Keitaro, Dazelin Rey, Kiano Tiger Wong, Kenzo Eldrago Wong, Amanda Soekasah, Giovani Tobi', 
        ]);

         Film::create([
            'judul' => 'Chainsaw Man', 
            'sinopsis' => 'Dalam perang brutal antara iblis, pemburu, dan musuh rahasia, seorang gadis misterius bernama Reze (Reina Ueda) masuk ke dunia Denji (Kikunosuke Toya). Denji menghadapi pertempuran paling mematikan yang pernah ada, dipicu oleh cinta di dunia di mana bertahan hidup tidak mengenal aturan.', 
            'durasi_menit' => 100, 
            'rating' => '13+', 
            'genre' => 'Animation', 
            'status_tayang' => 'Playing Now', 
            'poster_url' => '3.jpg', 
            'trailer_url' => 'https://youtu.be/EPaoHkV0dYw', 
            'produser' => 'Keisuke Seshimo, Makoto Kimura, Manabu Otsuka',
            'sutradara' => 'Tatsuya Yoshihara',
            'penulis' => 'Hiroshi Seko, Tatsuki Fujimoto',
            'produksi' => 'Columbia Pictures',
            'cast_list' => 'Kikunosuke Toya, Reina Ueda, Tomori Kusunoki, Shiori Izawa, Shogo Sakata, Ai Fairouz, Karin Takahashi', 
        ]);

         Film::create([
            'judul' => 'Kang Solah Kang mak X Nenek Gayung', 
            'sinopsis' => 'SOLAH VINCENZIO (Rigen Rakelna) pulang ke kampungnya, ditemani Fajrul (Indra Jegel), Jaka (Tora Sudiro), dan Supra (Indro Warkop). Solah membayangkan kepulangannya akan disambut sebagai pahlawan, tapi ternyata malah dikira setan. Solah semakin sedih ketika gadis yang dia suka sejak lama, DARA GONZALES (Davina Karamoy), akan dinikahi oleh adik kandungnya sendiri, IQBAL (Kenzy Taulany). Namun pernikahan mereka terganggu karena diteror oleh kemunculan sosok setan, Nenek Gayung, setan pemandi jenazah yang sedang mencari korban untuk dimandikan. Satu-satunya cara untuk menyelamatkan pernikahan Iqbal dan Dara adalah jika Solah dan teman-temannya mau menghadapi Nenek Gayung dan meminta bantuan KangMas Pusi (Andre Taulany).', 
            'durasi_menit' => 116, 
            'rating' => '13+', 
            'genre' => 'Comedy', 
            'status_tayang' => 'Playing Now', 
            'poster_url' => '4.jpg', 
            'trailer_url' => 'https://youtu.be/vm_N0vdsDYU', 
            'produser' => 'Frederica',
            'sutradara' => 'Herwin Novianto',
            'penulis' => 'Herwin Novianto',
            'produksi' => 'Falcon Pictures',
            'cast_list' => 'Andre Taulany, Rigen Rakelna, Indra Jegel, Indro Warkop, Tora Sudiro, Davina Karamoy, Asri Welas, Indy Barends, Kenzy Taulany', 
        ]);

         Film::create([
            'judul' => 'Dia Bukan Ibu', 
            'sinopsis' => 'Dua tahun setelah perceraian orang tuanya, Vira (Aurora Ribero) tinggal bersama ibu dan adiknya, Dino (Ali Fikry). Yanti (Artika Sari Devi), sang ibu, yang dulu hancur karena ditinggal oleh suaminya kini berubah drastis dan menjadi lebih ceria, cantik, sukses, dan mampu membeli rumah baru sekaligus membuka salon yang selalu ramai meskipun lokasinya tidak strategis.
            Namun di balik keberhasilan itu, Vira mulai menyaksikan hal-hal aneh: ibunya melukai pelanggan, hingga berani untuk menyerang Dino dan keesokannya akan berperilaku seperti tidak terjadi apa-apa. Vira memutuskan untuk mencari tahu apa yang sebenarnya terjadi kepada sang Ibu, dan apakah di dalam diri Ibu, masih Ibu?', 
            'durasi_menit' => 119, 
            'rating' => '17+', 
            'genre' => 'Horror', 
            'status_tayang' => 'Playing Now', 
            'poster_url' => '5.jpg', 
            'trailer_url' => 'https://youtu.be/qKt7XFeSmRs?si=JaYy35Ed0nBA1FRh', 
            'produser' => 'Raam Punjabi',
            'sutradara' => 'Randolph Zaini',
            'penulis' => 'Randolph Zaini, Titien Wattimena, Beta Ingrid Ayu',
            'produksi' => 'MVP Pictures',
            'cast_list' => 'Artika Sari Devi, Aurora Ribero, Ali Fikry, Khiva Rayanka, Sita Nursanti, Dian Sidik, Husein Alatas', 
        ]);

         Film::create([
            'judul' => 'Perempuan Pembawa Sial', 
            'sinopsis' => 'Mirah adalah wanita muda yang menyimpan rahasia kelam berupa kutukan yang membuat setiap pria yang mendekatinya menemui ajal dengan cara tragis. Hidupnya dipenuhi rasa sepi karena masyarakat memilih menjauh darinya.
            Hingga suatu hari, Mirah bertemu dengan Bana, pemilik warung Padang yang diperankan Morgan Oey. Berbeda dengan orang lain, Bana justru menerima Mirah dengan sabar, hingga tumbuh perasaan cinta di antara mereka. Namun, Mirah sadar bahwa kutukan yang menimpanya berasal dari saudara tirinya, Puti, yang menurunkan kutukan Bahu Laweyan. Keinginan balas dendam pun membuatnya merencanakan untuk menghancurkan kebahagiaan Puti dengan membunuh suaminya.', 
            'durasi_menit' => 98, 
            'rating' => '17+', 
            'genre' => 'Horror', 
            'status_tayang' => 'Playing Now', 
            'poster_url' => '6.jpg', 
            'trailer_url' => 'https://youtu.be/9_XO7ob7yR4?si=MbA6wfMfl0WA2VR6', 
            'produser' => 'Susanti Dewi',
            'sutradara' => 'Fajar Nugros',
            'penulis' => 'Fajar Nugros',
            'produksi' => ' IDN Pictures',
            'cast_list' => 'Raihaanun, Clara Bernadeth, Morgan Oey, Didik Nini Thowok, Aurra Kharisma, Rukman Rosadi, Benidictus Siregar, Banyu Bening', 
        ]);

         Film::create([
            'judul' => 'Jadi Tuh Barang', 
            'sinopsis' => 'BONAR (Oki Rengga), seorang pria muda yang sedang menghadapi masa sulit. Baru saja diputuskan oleh pacarnya, CANTIKA (Beby Tsabina), karena dianggap tidak serius dan kurang berjuang dalam hubungan mereka. Bonar harus berhadapan dengan berbagai tekanan hidup. Di tengah keputusasaannya, Bonar didampingi oleh dua sahabatnya, AWANG (Dicky Difie) dan WONGSO (Steven Wongso), yang selalu berada di sampingnya meski mereka sendiri menghadapi tantangan hidup masing-masing.
            Ketiganya menemukan cara yang tak terduga untuk bertahan hidup ketika mereka mendapatkan tawaran menjadi pawang hujan dari ZARA (Arafah Rianti).
            Meski awalnya skeptis karena pekerjaan tersebut dianggap klenik, mereka tetap menerima tawaran tersebut demi uang. Bonar yang terdesak oleh masalah finansial, dan dorongan untuk segera melamar Cantika ke IBUnya (Nurul Arifin), Awang dengan masalah ekonomi keluarga, dan Wongso dengan masalah investasinya.
            Bonar akhirnya setuju untuk memerankan peran pawang hujan. Dengan cara-cara kreatif dan konyol, melalui orang sakti bernama KI RENGGA (Arief Didu), Bonar berusaha memenuhi tugasnya, dan harus bersaing dengan ARNOLD (Ge Pamungkas) yang telah lebih dulu mengantongi restu Ibu Cantika.', 
            'durasi_menit' => 101, 
            'rating' => '13+', 
            'genre' => 'Comedy', 
            'status_tayang' => 'Playing Now', 
            'poster_url' => '7.jpg', 
            'trailer_url' => 'https://youtu.be/MLiB5Lcf-c8?si=7XJCU8A7_H3BlGs9', 
            'produser' => 'Kemal Palevi, Erick Bastian',
            'sutradara' => 'Kemal Palevi',
            'penulis' => 'David Nurbianto, Luqman Baehaqi, Iam Renzia',
            'produksi' => 'Komik Pictures',
            'cast_list' => 'Oki Rengga, Dicky Difie, Steven Wongso, Beby Tsabina, Arafah Rianty, Ge Pamungkas, Arief Didu, Fico Fachriza, Nurul Arifin, Nathalie Sarah', 
        ]);

         Film::create([
            'judul' => 'Yakin Nikah', 
            'sinopsis' => 'Perjalanan NIKEN (Enzy Storia) menempuh realita hubungan masa kini, yang penuh ujian dari masa lalu, keluarga, ekspektasi sosial, hingga mimpi pribadi yang belum tercapai, melalui konflik yang kocak, awkward, atau bahkan menyakitkan. Ekspektasi akan pernikahan yang sempurna membuatnya semakin dilema Yakin Nikah? atau Yakin Nikah', 
            'durasi_menit' => 108, 
            'rating' => '13+', 
            'genre' => 'Romance', 
            'status_tayang' => 'Upcoming', 
            'poster_url' => '8.jpg', 
            'trailer_url' => 'https://youtu.be/WHCN5rosXE8?si=sALJNHkq3KC4Q3zd', 
            'produser' => 'Shierly Kosasih, Ervina Isleyen',
            'sutradara' => 'Pritagita Arianegara',
            'penulis' => 'Bene Dion Rajagukguk, Sigit Sulistyo, Erwin Wu',
            'produksi' => 'Adhya Pictures',
            'cast_list' => 'Enzy Storia, Maxime Bouttier, Jourdy Pranata, Amanda Rigby, Tora Sudiro, Ersa Mayori, Agnes Naomi, Lukman Sardi, Arya Vasco, Nadine Emanuella, Izabel Jahja, Mike Lucock, Jerome Kurnia, Indian Akbar', 
        ]);

        Film::create([
            'judul' => 'Jangan Panggil Mama Kafir', 
            'sinopsis' => 'kisah cinta Maria, (Michelle Ziudith) dan Fafat (Giorgino Abraham). Siapa sangka, keduanya memiliki keyakinan berbeda. Namun, pertemuan tak terduga justru membawa hubungan mereka semakin serius.
            Ya, meskipun perbedaan keyakinan menjadi tantangan besar, cinta mereka tumbuh kuat. Keduanya memutuskan untuk menikah, namun dengan tetap pada keyakinan mereka. Dari pernikahan beda agama ini, kebahagiaan mereka semakin lengkap dengan kehadiran seorang putri cantik bernama Laila, yang diperankan oleh Humaira Jahra.', 
            'durasi_menit' => 110, 
            'rating' => '13+', 
            'genre' => 'Drama', 
            'status_tayang' => 'Upcoming', 
            'poster_url' => '9.jpg', 
            'trailer_url' => 'https://youtu.be/rNE8IHQHh3s?si=5eXHGqJCpNugyabM', 
            'produser' => 'Yoen K, Zoya Salsabila',
            'sutradara' => 'Dyan Sunu Prastowo',
            'penulis' => 'Archie Hekagery, Lina Nurmalina',
            'produksi' => 'Maxima Pictures, Rocket Entertainment',
            'cast_list' => 'Michelle Ziudith, Giorgino Abraham, Elma Theana, Humaira Jahra, Indra Birowo, TJ Ruth, Gilbert Pattiruhu, Prastiwi Dwiarti, Kaneishia Jusuf, Dira Sugandi', 
        ]);

        Film::create([
            'judul' => 'Air Mata Di Ujung Sejadah 2', 
            'sinopsis' => 'Bertahun-tahun lalu, Aqilla (Titi Kamal) membuat keputusan yang sangat berat: merelakan anak kandungnya, Baskara (Faqih Alaydrus), untuk diadopsi oleh pasangan Arif dan Yumna (Citra Kirana) di Kota Solo. Sejak saat itu, ia hanya bisa memantau tumbuh kembang Baskara melalui unggahan Yumna di media sosial, berharap takdir suatu hari akan mempertemukan mereka kembali.
            Namun, harapan itu berubah menjadi kecemasan saat akun media sosial Yumna mendadak mati. Panik, Aqilla memberanikan diri pergi ke Solo. Ia tak menyangka kunjungannya akan membongkar sebuah fakta mengejutkan: Arif telah meninggal dunia setelah sempat mengalami koma.
            Kini, bukan hanya kecemasan yang ia rasakan, tapi juga sebuah konflik besar. Hak asuh Baskara menjadi rebutan antara Aqilla, ibu kandungnya, dan Yumna, ibu angkat yang selama ini merawatnya. Situasi semakin runyam dengan kehadiran Fathan (Daffa Wardhana), adik mendiang Arif. Ia yang ditugaskan untuk menjaga Baskara justru menaruh simpati pada Aqilla.
            Di tengah pertikaian sengit, satu rahasia besar terkuak. Sebuah rahasia yang telah lama disembunyikan akhirnya terbongkar, mengubah hidup mereka selamanya. Lantas, siapakah yang akhirnya berhak atas Baskara? Dan apakah Baskara siap menerima kenyataan bahwa Aqilla adalah ibu kandungnya?', 
            'durasi_menit' => 105, 
            'rating' => '13+', 
            'genre' => 'Drama', 
            'status_tayang' => 'Upcoming', 
            'poster_url' => '10.jpg', 
            'trailer_url' => 'https://youtu.be/4Jo4me9Osoo?si=I4bTmG8xLNYoQ7f0', 
            'produser' => 'Ronny Irawan',
            'sutradara' => 'Key Mangunsong',
            'penulis' => 'Eginina Oey, Henovia Rosalinda, Key Mangunsong, Ronny Irawan',
            'produksi' => 'Beehave Pictures, Legacy Pictures, Sigma Films, Kai Films, AZ Films',
            'cast_list' => 'Titi Kamal, Citra Kirana, Faqih Alaydrus, Daffa Wardhana, Jenny Rachman, Mbok Tun', 
        ]);

        Film::create([
            'judul' => 'Si Paling Aktor', 
            'sinopsis' => 'Gilang (Jourdy Pranata) sudah belasan tahun jadi figuran, suatu hari ketika sedang syuting film horror ia diculik bersama pemeran utama laki-laki, pemeran utama perempuan, dan sutradara film tersebut. Para penculik berniat menghabisi mereka setelah meminta tebusan. Untungnya, bekal pengalaman Gilang selama menjadi figuran memberinya harapan untuk selamat!', 
            'durasi_menit' => 112, 
            'rating' => '13+', 
            'genre' => 'Comedy', 
            'status_tayang' => 'Upcoming', 
            'poster_url' => '11.jpg', 
            'trailer_url' => 'https://youtu.be/FMwIJVwM56E?si=3-uHnChT-YT7eiOQ', 
            'produser' => 'Manoj Punjabi',
            'sutradara' => 'Ody Harahap',
            'penulis' => 'Adhitya Mulya',
            'produksi' => 'MD Pictures',
            'cast_list' => 'Jourdy Pranata, Cut Beby Tsabina, Kevin Julio, Kenny Austin, Yeyen Lydia, Verdi Sulaeman, David Nurbianto, Andrew Lincoln Suleiman, Billyan El Kausha, Vidibulle, Indra Birowo', 
        ]);
    }
}