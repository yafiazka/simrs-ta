<?php

namespace Database\Seeders;

use App\Models\Penyakit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyakitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing data to prevent duplicates
        if (DB::getDriverName() === 'sqlite') {
            DB::table('penyakit')->delete();
        } else {
            DB::statement('TRUNCATE TABLE penyakit RESTART IDENTITY CASCADE');
        }

        // 1. Common real ICD-10 codes with Indonesian/English names and descriptions
        $commonDiseases = [
            'A00' => ['nm' => 'Kolera', 'en' => 'Cholera', 'ciri' => 'Diare berair sangat encer seperti air cucian beras, muntah, dehidrasi berat.', 'ket' => 'Penyakit Menular Lewat Air', 'status' => 'Menular', 'ktg' => 'K01'],
            'A01' => ['nm' => 'Demam Tifoid dan Paratifoid', 'en' => 'Typhoid and paratyphoid fevers', 'ciri' => 'Demam bertahap naik terutama malam hari, sakit perut, sembelit atau diare, lidah kotor.', 'ket' => 'Penyakit Menular Lewat Makanan', 'status' => 'Menular', 'ktg' => 'K01'],
            'A02' => ['nm' => 'Infeksi Salmonella Lainnya', 'en' => 'Other salmonella infections', 'ciri' => 'Diare, demam kram perut dalam 12-72 jam setelah terpapar.', 'ket' => 'Penyakit Infeksi Bakteri', 'status' => 'Menular', 'ktg' => 'K01'],
            'A03' => ['nm' => 'Shigellosis (Disentri Basiler)', 'en' => 'Shigellosis', 'ciri' => 'Diare berdarah dan berlendir, kram perut hebat, demam tinggi, tenesmus.', 'ket' => 'Penyakit Menular Lewat Kontak', 'status' => 'Menular', 'ktg' => 'K01'],
            'A04' => ['nm' => 'Infeksi Usus Bakteri Lainnya', 'en' => 'Other bacterial intestinal infections', 'ciri' => 'Sakit perut kembung, diare encer berulang.', 'ket' => 'Penyakit Infeksi Bakteri', 'status' => 'Menular', 'ktg' => 'K01'],
            'A05' => ['nm' => 'Keracunan Makanan Bakteri Lainnya', 'en' => 'Other bacterial foodborne intoxications', 'ciri' => 'Mual, muntah mendadak, diare setelah makan makanan tertentu.', 'ket' => 'Keracunan Makanan', 'status' => 'Tidak Menular', 'ktg' => 'K01'],
            'A06' => ['nm' => 'Amoebiasis (Disentri Amoeba)', 'en' => 'Amoebiasis', 'ciri' => 'Diare berlendir lambat, nyeri perut bawah, penurunan nafsu makan.', 'ket' => 'Infeksi Parasit Usus', 'status' => 'Menular', 'ktg' => 'K01'],
            'A07' => ['nm' => 'Penyakit Usus Protozoa Lainnya', 'en' => 'Other protozoal intestinal diseases', 'ciri' => 'Diare encer berbau busuk, kembung dan sering kentut.', 'ket' => 'Infeksi Parasit Usus', 'status' => 'Menular', 'ktg' => 'K01'],
            'A08' => ['nm' => 'Infeksi Usus Virus dan Lainnya', 'en' => 'Viral and other specified intestinal infections', 'ciri' => 'Muntah diikuti diare cair, demam ringan, sering pada anak-anak (Rotavirus).', 'ket' => 'Gastroenteritis Viral', 'status' => 'Menular', 'ktg' => 'K01'],
            'A09' => ['nm' => 'Diare dan Gastroenteritis Oleh Penyebab Infeksi', 'en' => 'Diarrhoea and gastroenteritis of infectious origin', 'ciri' => 'Buang air besar cair lebih dari 3 kali sehari, lemas, dehidrasi, mual muntah.', 'ket' => 'Penyakit Menular Lewat Makanan/Air', 'status' => 'Menular', 'ktg' => 'K01'],
            'A15' => ['nm' => 'Tuberkulosis Paru (TBC)', 'en' => 'Respiratory tuberculosis, bacteriologically and histologically confirmed', 'ciri' => 'Batuk berdahak lebih dari 2 minggu, demam subfebris, penurunan berat badan, berkeringat di malam hari.', 'ket' => 'Penyakit Menular Lewat Udara (Droplet)', 'status' => 'Menular', 'ktg' => 'K02'],
            'A16' => ['nm' => 'Tuberkulosis Paru Klinis (TBC Tanpa Konfirmasi)', 'en' => 'Respiratory tuberculosis, not confirmed bacteriologically or histologically', 'ciri' => 'Gejala klinis TBC dengan hasil dahak negatif namun rontgen mendukung.', 'ket' => 'Penyakit Menular Lewat Udara', 'status' => 'Menular', 'ktg' => 'K02'],
            'A30' => ['nm' => 'Kusta (Lepra)', 'en' => 'Leprosy [Hansen\'s disease]', 'ciri' => 'Bercak putih/merah mati rasa di kulit, penebalan saraf tepi, kelemahan otot.', 'ket' => 'Penyakit Bakteri Kronis', 'status' => 'Menular', 'ktg' => 'K02'],
            'A37' => ['nm' => 'Batuk Rejan (Pertusis)', 'en' => 'Whooping cough', 'ciri' => 'Batuk beruntun cepat diakhiri bunyi napas melengking bernada tinggi.', 'ket' => 'Penyakit Infeksi Udara', 'status' => 'Menular', 'ktg' => 'K02'],
            'A90' => ['nm' => 'Demam Dengue', 'en' => 'Dengue fever [classical dengue]', 'ciri' => 'Demam tinggi mendadak mendatar, nyeri sendi/otot hebat, nyeri belakang mata.', 'ket' => 'Penyakit Tular Vektor (Nyamuk)', 'status' => 'Menular', 'ktg' => 'K01'],
            'A91' => ['nm' => 'Demam Berdarah Dengue (DBD)', 'en' => 'Dengue haemorrhagic fever', 'ciri' => 'Demam tinggi, manifestasi perdarahan (petekie, mimisan), kebocoran plasma.', 'ket' => 'Penyakit Tular Vektor', 'status' => 'Menular', 'ktg' => 'K01'],
            'B00' => ['nm' => 'Infeksi Virus Herpes Simpleks', 'en' => 'Herpesviral [herpes simplex] infections', 'ciri' => 'Lenting berkelompok di sekitar bibir atau alat kelamin yang terasa perih.', 'ket' => 'Infeksi Virus Kulit', 'status' => 'Menular', 'ktg' => 'K03'],
            'B01' => ['nm' => 'Varisela (Cacar Air)', 'en' => 'Varicella (Chickenpox)', 'ciri' => 'Demam, muncul ruam merah gatal yang berubah menjadi lenting berisi cairan di seluruh tubuh.', 'ket' => 'Penyakit Menular Lewat Kontak Langsung/Udara', 'status' => 'Menular', 'ktg' => 'K03'],
            'B02' => ['nm' => 'Herpes Zoster (Cacar Ular)', 'en' => 'Herpes zoster [shingles]', 'ciri' => 'Lenting berkelompok di satu sisi tubuh mengikuti persarafan, nyeri radikuler hebat.', 'ket' => 'Infeksi Reaktivasi Virus', 'status' => 'Tidak Menular', 'ktg' => 'K03'],
            'B05' => ['nm' => 'Campak (Morbili)', 'en' => 'Measles', 'ciri' => 'Demam tinggi, batuk, pilek, mata merah (konjungtivitis), disusul ruam makulopapular merata.', 'ket' => 'Infeksi Virus Udara', 'status' => 'Menular', 'ktg' => 'K03'],
            'B15' => ['nm' => 'Hepatitis A Akut', 'en' => 'Acute hepatitis A', 'ciri' => 'Demam, lemas, hilang nafsu makan, urin berwarna gelap, mata/kulit menguning (ikterus).', 'ket' => 'Penyakit Menular Oro-Fekal', 'status' => 'Menular', 'ktg' => 'K01'],
            'B16' => ['nm' => 'Hepatitis B Akut', 'en' => 'Acute hepatitis B', 'ciri' => 'Mual, lemas, ikterus, ditularkan lewat darah/cairan tubuh.', 'ket' => 'Penyakit Menular Darah', 'status' => 'Menular', 'ktg' => 'K01'],
            'B18' => ['nm' => 'Hepatitis Virus Kronis', 'en' => 'Chronic viral hepatitis', 'ciri' => 'Gejala hepatitis menetap lebih dari 6 bulan, risiko sirosis.', 'ket' => 'Penyakit Kronis Liver', 'status' => 'Menular', 'ktg' => 'K01'],
            'B26' => ['nm' => 'Mumps (Gondongan)', 'en' => 'Mumps', 'ciri' => 'Pembengkakan nyeri pada satu atau kedua kelenjar parotis air liur depan telinga.', 'ket' => 'Penyakit Virus Menular', 'status' => 'Menular', 'ktg' => 'K03'],
            'B35' => ['nm' => 'Dermatofitosis (Tinea / Jamur Kulit)', 'en' => 'Dermatophytosis', 'ciri' => 'Bercak kemerahan melingkar dengan tepi lebih aktif dan gatal terutama saat berkeringat.', 'ket' => 'Infeksi Jamur Kulit', 'status' => 'Menular', 'ktg' => 'K03'],
            'B50' => ['nm' => 'Malaria Plasmodium Falciparum', 'en' => 'Plasmodium falciparum malaria', 'ciri' => 'Demam tinggi menggigil berkala, anemia, berisiko malaria serebral (kejang/koma).', 'ket' => 'Malaria Berat', 'status' => 'Menular', 'ktg' => 'K01'],
            'B54' => ['nm' => 'Malaria Tidak Spesifik', 'en' => 'Unspecified malaria', 'ciri' => 'Demam menggigil berkeringat, anemia, riwayat tinggal di daerah endemis.', 'ket' => 'Malaria Ringan', 'status' => 'Menular', 'ktg' => 'K01'],
            'C00' => ['nm' => 'Kanker Bibir', 'en' => 'Malignant neoplasm of lip', 'ciri' => 'Benjolan keras atau sariawan yang tidak kunjung sembuh di bibir.', 'ket' => 'Neoplasma Ganas', 'status' => 'Tidak Menular', 'ktg' => 'K03'],
            'D50' => ['nm' => 'Anemia Defisiensi Besi', 'en' => 'Iron deficiency anaemia', 'ciri' => '5L (Lemas, letih, lesu, lelah, lalai), pucat pada konjungtiva dan kuku.', 'ket' => 'Defisiensi Nutrisi', 'status' => 'Tidak Menular', 'ktg' => 'K04'],
            'D64' => ['nm' => 'Anemia Lainnya', 'en' => 'Other anaemias', 'ciri' => 'Lemas, wajah pucat, pusing berputar, jantung berdebar cepat.', 'ket' => 'Gangguan Sel Darah Merah', 'status' => 'Tidak Menular', 'ktg' => 'K04'],
            'E05' => ['nm' => 'Tirotoksikosis (Hipertiroid)', 'en' => 'Thyrotoxicosis [hyperthyroidism]', 'ciri' => 'Jantung berdebar, tangan gemetar (tremor), berat badan turun walau banyak makan, mata menonjol.', 'ket' => 'Gangguan Endokrin', 'status' => 'Tidak Menular', 'ktg' => 'K04'],
            'E10' => ['nm' => 'Diabetes Melitus Tipe 1', 'en' => 'Type 1 diabetes mellitus', 'ciri' => 'Polidipsi, poliuri, polifagi sejak usia muda, bergantung insulin.', 'ket' => 'Gangguan Endokrin', 'status' => 'Tidak Menular', 'ktg' => 'K04'],
            'E11' => ['nm' => 'Diabetes Melitus Tipe 2', 'en' => 'Non-insulin-dependent diabetes mellitus', 'ciri' => 'Sering haus (polidipsi), sering kencing (poliuri), sering lapar (polifagi), berat badan turun drastis tanpa sebab.', 'ket' => 'Penyakit Metabolik Tidak Menular', 'status' => 'Tidak Menular', 'ktg' => 'K04'],
            'E14' => ['nm' => 'Diabetes Melitus Tidak Spesifik', 'en' => 'Unspecified diabetes mellitus', 'ciri' => 'Gula darah sewaktu tinggi disertai gejala klasik diabetes.', 'ket' => 'Gangguan Metabolik', 'status' => 'Tidak Menular', 'ktg' => 'K04'],
            'E66' => ['nm' => 'Obesitas', 'en' => 'Obesity', 'ciri' => 'Kelebihan berat badan secara ekstrem dengan indeks massa tubuh (IMT) > 30.', 'ket' => 'Gangguan Nutrisi', 'status' => 'Tidak Menular', 'ktg' => 'K04'],
            'E78' => ['nm' => 'Gangguan Metabolisme Lipoprotein (Kolesterol)', 'en' => 'Disorders of lipoprotein metabolism and other lipidaemias', 'ciri' => 'Kadar kolesterol total atau LDL tinggi di dalam darah.', 'ket' => 'Gangguan Lipid', 'status' => 'Tidak Menular', 'ktg' => 'K04'],
            'F20' => ['nm' => 'Skizofrenia', 'en' => 'Schizophrenia', 'ciri' => 'Halusinasi pendengaran/visual, delusi/waham, menarik diri dari sosial.', 'ket' => 'Gangguan Jiwa Psikotik', 'status' => 'Tidak Menular', 'ktg' => 'K04'],
            'F32' => ['nm' => 'Episode Depresi', 'en' => 'Depressive episode', 'ciri' => 'Sedih mendalam menetap > 2 minggu, kehilangan minat, energi menurun drastis.', 'ket' => 'Gangguan Suasana Perasaan', 'status' => 'Tidak Menular', 'ktg' => 'K04'],
            'F41' => ['nm' => 'Gangguan Kecemasan Lainnya', 'en' => 'Other anxiety disorders', 'ciri' => 'Khawatir berlebihan kronis, jantung berdebar, keringat dingin, tegang otot.', 'ket' => 'Gangguan Neurotik', 'status' => 'Tidak Menular', 'ktg' => 'K04'],
            'G40' => ['nm' => 'Epilepsi', 'en' => 'Epilepsy', 'ciri' => 'Kejang berulang tanpa rangsangan mendadak, penurunan kesadaran sementara.', 'ket' => 'Gangguan Saraf', 'status' => 'Tidak Menular', 'ktg' => 'K04'],
            'G43' => ['nm' => 'Migrain', 'en' => 'Migraine', 'ciri' => 'Nyeri kepala berdenyut satu sisi (unilateral), mual, sensitif cahaya/suara.', 'ket' => 'Sindrom Nyeri Kepala', 'status' => 'Tidak Menular', 'ktg' => 'K04'],
            'G44' => ['nm' => 'Sindrom Sakit Kepala Lainnya', 'en' => 'Other headache syndromes', 'ciri' => 'Sakit kepala tegang (tension headache) seperti diikat di dahi.', 'ket' => 'Sindrom Nyeri Kepala', 'status' => 'Tidak Menular', 'ktg' => 'K04'],
            'H10' => ['nm' => 'Konjungtivitis (Sakit Mata)', 'en' => 'Conjunctivitis', 'ciri' => 'Mata merah, berair, gatal, terasa mengganjal, sekret mata (belekan) berlebih.', 'ket' => 'Penyakit Mata Luar', 'status' => 'Menular', 'ktg' => 'K02'],
            'H60' => ['nm' => 'Otitis Eksterna', 'en' => 'Otitis externa', 'ciri' => 'Nyeri liang telinga luar terutama saat daun telinga ditarik, gatal, keluar cairan.', 'ket' => 'Penyakit Telinga Luar', 'status' => 'Tidak Menular', 'ktg' => 'K02'],
            'H65' => ['nm' => 'Otitis Media Non-Supuratif', 'en' => 'Nonsuppurative otitis media', 'ciri' => 'Telinga terasa penuh, penurunan pendengaran setelah batuk pilek.', 'ket' => 'Penyakit Telinga Tengah', 'status' => 'Tidak Menular', 'ktg' => 'K02'],
            'H66' => ['nm' => 'Otitis Media Supuratif (Congek)', 'en' => 'Suppurative and unspecified otitis media', 'ciri' => 'Keluar cairan nanah berbau dari liang telinga akibat gendang telinga pecah.', 'ket' => 'Penyakit Telinga Tengah', 'status' => 'Menular', 'ktg' => 'K02'],
            'I10' => ['nm' => 'Hipertensi Esensial (Primer)', 'en' => 'Essential (primary) hypertension', 'ciri' => 'Sakit kepala, tengkuk terasa kaku, pusing, kelelahan, tekanan darah >= 140/90 mmHg.', 'ket' => 'Penyakit Kardiovaskular Tidak Menular', 'status' => 'Tidak Menular', 'ktg' => 'K05'],
            'I11' => ['nm' => 'Penyakit Jantung Hipertensi', 'en' => 'Hypertensive heart disease', 'ciri' => 'Sesak napas saat beraktivitas berat, lelah, riwayat hipertensi menahun.', 'ket' => 'Penyakit Jantung Kronis', 'status' => 'Tidak Menular', 'ktg' => 'K05'],
            'I20' => ['nm' => 'Angina Pektoris', 'en' => 'Angina pectoris', 'ciri' => 'Nyeri dada kiri seperti dihimpit yang hilang dengan istirahat.', 'ket' => 'Penyakit Jantung Koroner', 'status' => 'Tidak Menular', 'ktg' => 'K05'],
            'I21' => ['nm' => 'Infark Miokard Akut (Serangan Jantung)', 'en' => 'Acute myocardial infarction', 'ciri' => 'Nyeri dada kiri seperti ditekan menjalar ke lengan kiri atau rahang, sesak napas, keringat dingin.', 'ket' => 'Penyakit Jantung Akut Tidak Menular', 'status' => 'Tidak Menular', 'ktg' => 'K05'],
            'I50' => ['nm' => 'Gagal Jantung', 'en' => 'Heart failure', 'ciri' => 'Sesak napas saat berbaring telentang, bengkak pada kedua tungkai kaki.', 'ket' => 'Gagal Organ Jantung', 'status' => 'Tidak Menular', 'ktg' => 'K05'],
            'I64' => ['nm' => 'Stroke Tidak Spesifik', 'en' => 'Stroke, not specified as haemorrhage or infarction', 'ciri' => 'Kelemahan anggota gerak sesisi mendadak, bicara pelo, mulut mencong.', 'ket' => 'Penyakit Serebrovaskular', 'status' => 'Tidak Menular', 'ktg' => 'K05'],
            'J00' => ['nm' => 'Nasofaringitis Akut (Common Cold / Pilek)', 'en' => 'Acute nasopharyngitis [common cold]', 'ciri' => 'Bersin-bersin, hidung tersumbat atau berair, sakit tenggorokan ringan, badan pegal.', 'ket' => 'Penyakit Menular Lewat Udara', 'status' => 'Menular', 'ktg' => 'K02'],
            'J01' => ['nm' => 'Sinusitis Akut', 'en' => 'Acute sinusitis', 'ciri' => 'Nyeri wajah/pipi, hidung tersumbat, ingus kental berbau, demam.', 'ket' => 'Infeksi THT', 'status' => 'Menular', 'ktg' => 'K02'],
            'J02' => ['nm' => 'Faringitis Akut (Sakit Tenggorokan)', 'en' => 'Acute pharyngitis', 'ciri' => 'Nyeri saat menelan, tenggorokan merah dan kering, demam, kelenjar getah bening leher membesar.', 'ket' => 'Penyakit Menular Lewat Udara', 'status' => 'Menular', 'ktg' => 'K02'],
            'J03' => ['nm' => 'Tonsilitis Akut (Amandel)', 'en' => 'Acute tonsillitis', 'ciri' => 'Amandel membengkak dan merah disertai bercak putih, demam, sulit menelan.', 'ket' => 'Penyakit Menular Lewat Udara', 'status' => 'Menular', 'ktg' => 'K02'],
            'J04' => ['nm' => 'Laringitis dan Trakeitis Akut', 'en' => 'Acute laryngitis and tracheitis', 'ciri' => 'Suara serak bahkan hilang, batuk menggonggong, nyeri tenggorokan.', 'ket' => 'Infeksi Saluran Pernapasan', 'status' => 'Menular', 'ktg' => 'K02'],
            'J06' => ['nm' => 'Infeksi Saluran Pernapasan Akut Atas (ISPA)', 'en' => 'Acute upper respiratory infection, unspecified', 'ciri' => 'Batuk, pilek, bersin-bersin, sakit tenggorokan, demam ringan.', 'ket' => 'Penyakit Menular Lewat Udara', 'status' => 'Menular', 'ktg' => 'K02'],
            'J18' => ['nm' => 'Pneumonia Terbuka (Infeksi Paru)', 'en' => 'Pneumonia, unspecified organism', 'ciri' => 'Batuk berdahak kental/kuning-hijau, demam menggigil, sesak napas berat, nyeri dada saat napas.', 'ket' => 'Infeksi Paru Berat', 'status' => 'Menular', 'ktg' => 'K02'],
            'J20' => ['nm' => 'Bronkitis Akut', 'en' => 'Acute bronchitis', 'ciri' => 'Batuk kering yang berkembang menjadi berdahak, sesak ringan setelah batuk.', 'ket' => 'Infeksi Bronkus Paru', 'status' => 'Menular', 'ktg' => 'K02'],
            'J30' => ['nm' => 'Rinitis Alergi dan Vasomotor', 'en' => 'Vasomotor and allergic rhinitis', 'ciri' => 'Bersin beruntun pagi hari, hidung meler encer jernih dipicu debu/dingin.', 'ket' => 'Penyakit Alergi Pernapasan', 'status' => 'Tidak Menular', 'ktg' => 'K06'],
            'J45' => ['nm' => 'Asma Bronkial', 'en' => 'Asthma', 'ciri' => 'Sesak napas berulang disertai bunyi mengik (wheezing), batuk-batuk terutama malam/dini hari.', 'ket' => 'Penyakit Alergi Kronis Tidak Menular', 'status' => 'Tidak Menular', 'ktg' => 'K06'],
            'K29' => ['nm' => 'Gastritis (Sakit Maag)', 'en' => 'Gastritis, unspecified', 'ciri' => 'Nyeri ulu hati, mual, muntah, perut kembung, terasa cepat kenyang saat makan.', 'ket' => 'Penyakit Pencernaan Tidak Menular', 'status' => 'Tidak Menular', 'ktg' => 'K07'],
            'K30' => ['nm' => 'Dispepsia', 'en' => 'Dyspepsia', 'ciri' => 'Rasa tidak nyaman di perut bagian atas, begah setelah makan, cepat kenyang.', 'ket' => 'Penyakit Pencernaan Tidak Menular', 'status' => 'Tidak Menular', 'ktg' => 'K07'],
            'K35' => ['nm' => 'Apendisitis Akut (Usus Buntu)', 'en' => 'Acute appendicitis', 'ciri' => 'Nyeri perut kanan bawah hebat, mual, demam ringan, nyeri tekan lepas kanan bawah.', 'ket' => 'Bedah Pencernaan Akut', 'status' => 'Tidak Menular', 'ktg' => 'K07'],
            'L20' => ['nm' => 'Dermatitis Atopik (Eksim)', 'en' => 'Atopic dermatitis', 'ciri' => 'Kulit sangat kering, gatal, kemerahan kronis sering di area lipatan tangan/kaki.', 'ket' => 'Penyakit Kulit Alergi', 'status' => 'Tidak Menular', 'ktg' => 'K06'],
            'L23' => ['nm' => 'Dermatitis Kontak Alergi', 'en' => 'Allergic contact dermatitis', 'ciri' => 'Gatal kemerahan di area yang bersentuhan dengan zat alergen (logam, kosmetik).', 'ket' => 'Penyakit Kulit Alergi', 'status' => 'Tidak Menular', 'ktg' => 'K06'],
            'L24' => ['nm' => 'Dermatitis Kontak Iritan', 'en' => 'Irritant contact dermatitis', 'ciri' => 'Kulit perih kemerahan, pecah-pecah setelah terpapar zat sabun/deterjen kuat.', 'ket' => 'Kerusakan Kulit Kimia', 'status' => 'Tidak Menular', 'ktg' => 'K06'],
            'L30' => ['nm' => 'Dermatitis Lainnya', 'en' => 'Other dermatitis', 'ciri' => 'Bercak eksim basah atau kering disertai gatal kronis.', 'ket' => 'Gangguan Kulit Umum', 'status' => 'Tidak Menular', 'ktg' => 'K06'],
            'L50' => ['nm' => 'Urtikaria (Biduran)', 'en' => 'Urticaria', 'ciri' => 'Bentol-bentol kemerahan menonjol (wheals) gatal yang menyebar cepat.', 'ket' => 'Penyakit Kulit Alergi', 'status' => 'Tidak Menular', 'ktg' => 'K06'],
            'M10' => ['nm' => 'Gout (Asam Urat)', 'en' => 'Gout', 'ciri' => 'Nyeri sendi hebat mendadak (terutama ibu jari kaki), bengkak, merah, terasa panas.', 'ket' => 'Penyakit Sendi Metabolik Tidak Menular', 'status' => 'Tidak Menular', 'ktg' => 'K08'],
            'M13' => ['nm' => 'Artritis Lainnya', 'en' => 'Other arthritis', 'ciri' => 'Nyeri pada beberapa persendian disertai kekakuan.', 'ket' => 'Radang Sendi Kronis', 'status' => 'Tidak Menular', 'ktg' => 'K08'],
            'M15' => ['nm' => 'Poliartrosis', 'en' => 'Polyarthrosis', 'ciri' => 'Nyeri sendi kronis akibat pengikisan tulang rawan pada lansia.', 'ket' => 'Degeneratif Sendi', 'status' => 'Tidak Menular', 'ktg' => 'K08'],
            'M47' => ['nm' => 'Spondilosis (Kapur Tulang Belakang)', 'en' => 'Spondylosis', 'ciri' => 'Nyeri punggung bawah atau leher kronis yang memburuk saat tegak.', 'ket' => 'Degeneratif Tulang', 'status' => 'Tidak Menular', 'ktg' => 'K08'],
            'M79' => ['nm' => 'Mialgia (Nyeri Otot)', 'en' => 'Myalgia', 'ciri' => 'Nyeri atau kaku pada otot-otot tubuh, pegal-pegal setelah aktivitas.', 'ket' => 'Penyakit Muskuloskeletal Tidak Menular', 'status' => 'Tidak Menular', 'ktg' => 'K08'],
            'N39' => ['nm' => 'Infeksi Saluran Kemih (ISK)', 'en' => 'Other disorders of urinary system', 'ciri' => 'Nyeri atau rasa terbakar saat berkemih, sering kencing, anyang-anyangan, urin keruh.', 'ket' => 'Penyakit Saluran Kemih Tidak Menular', 'status' => 'Tidak Menular', 'ktg' => 'K09'],
            'N76' => ['nm' => 'Peradangan Vagina (Vaginitis)', 'en' => 'Other inflammation of vagina and vulva', 'ciri' => 'Keputihan abnormal berlebih, gatal, panas pada kemaluan wanita.', 'ket' => 'Radang Saluran Reproduksi', 'status' => 'Menular', 'ktg' => 'K09'],
            'O00' => ['nm' => 'Kehamilan Ektopik', 'en' => 'Ectopic pregnancy', 'ciri' => 'Nyeri perut bawah hebat pada ibu hamil muda disertai flek darah.', 'ket' => 'Darurat Obstetri', 'status' => 'Tidak Menular', 'ktg' => 'K09'],
            'P07' => ['nm' => 'Bayi Prematur (Kelahiran Kurang Bulan)', 'en' => 'Disorders related to short gestation and low birth weight, not elsewhere classified', 'ciri' => 'Bayi lahir dengan usia kehamilan < 37 minggu atau berat < 2500 gram.', 'ket' => 'Kondisi Perinatal', 'status' => 'Tidak Menular', 'ktg' => 'K09'],
            'Q00' => ['nm' => 'Anensefali (Kelainan Tabung Saraf)', 'en' => 'Anencephaly and similar malformations', 'ciri' => 'Bayi lahir tanpa sebagian besar tempurung kepala dan otak.', 'ket' => 'Malformasi Kongenital', 'status' => 'Tidak Menular', 'ktg' => 'K09'],
            'R50' => ['nm' => 'Demam yang Tidak Diketahui Penyebabnya', 'en' => 'Fever of other and unknown origin', 'ciri' => 'Suhu tubuh > 37.5 derajat Celcius tanpa disertai gejala khas lainnya.', 'ket' => 'Gejala/Tanda Umum', 'status' => 'Tidak Menular', 'ktg' => 'K10'],
            'R51' => ['nm' => 'Sakit Kepala / Pusing', 'en' => 'Headache', 'ciri' => 'Rasa nyeri atau tegang pada seluruh atau sebagian area kepala.', 'ket' => 'Gejala/Tanda Umum', 'status' => 'Tidak Menular', 'ktg' => 'K10'],
            'S00' => ['nm' => 'Cedera Kepala Ringan (Luka Lecet Kepala)', 'en' => 'Superficial injury of head', 'ciri' => 'Luka lecet, memar pada area wajah atau kulit kepala tanpa pingsan.', 'ket' => 'Cedera Trauma', 'status' => 'Tidak Menular', 'ktg' => 'K10'],
            'T14' => ['nm' => 'Cedera Bagian Tubuh Tidak Spesifik (Trauma)', 'en' => 'Injury of unspecified body region', 'ciri' => 'Luka gores, memar, bengkak setelah terbentur atau jatuh.', 'ket' => 'Cedera Trauma', 'status' => 'Tidak Menular', 'ktg' => 'K10'],
            'Z00' => ['nm' => 'Pemeriksaan Kesehatan Umum (General Check-up)', 'en' => 'General examination and investigation of persons without complaint or reported diagnosis', 'ciri' => 'Pemeriksaan rutin berkala tanpa keluhan sakit.', 'ket' => 'Faktor Pengaruh Status Kesehatan', 'status' => 'Tidak Menular', 'ktg' => 'K11'],
            'Z01' => ['nm' => 'Pemeriksaan Kesehatan Gigi Khusus', 'en' => 'Other special examinations and investigations of persons without complaint or reported diagnosis', 'ciri' => 'Pemeriksaan gigi berkala tanpa adanya keluhan nyeri hebat.', 'ket' => 'Faktor Pengaruh Status Kesehatan', 'status' => 'Tidak Menular', 'ktg' => 'K11'],
            'Z02' => ['nm' => 'Pemeriksaan Administratif (Pembuatan Surat Keterangan Sehat)', 'en' => 'Examination and encounter for administrative purposes', 'ciri' => 'Kunjungan pasien untuk membuat surat sehat/kir dokter.', 'ket' => 'Faktor Pengaruh Status Kesehatan', 'status' => 'Tidak Menular', 'ktg' => 'K11'],
        ];

        // 2. Generate all 3-digit categories from A00 to Z99 programmatically to cover complete index
        $allPenyakit = [];
        $chapters = range('A', 'Z');
        
        foreach ($chapters as $chapter) {
            for ($num = 0; $num <= 99; $num++) {
                $code = $chapter . str_pad($num, 2, '0', STR_PAD_LEFT);
                
                // If we already have specific details for this code, use them
                if (isset($commonDiseases[$code])) {
                    $allPenyakit[] = [
                        'kd_penyakit' => $code,
                        'nm_penyakit' => $commonDiseases[$code]['nm'],
                        'nama_penyakit_en' => $commonDiseases[$code]['en'],
                        'ciri_ciri' => $commonDiseases[$code]['ciri'],
                        'keterangan' => $commonDiseases[$code]['ket'],
                        'kd_ktg' => $commonDiseases[$code]['ktg'],
                        'status' => $commonDiseases[$code]['status'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    // Fallback for other codes (creates a complete index)
                    $allPenyakit[] = [
                        'kd_penyakit' => $code,
                        'nm_penyakit' => "Penyakit ICD-10 Kategori {$code}",
                        'nama_penyakit_en' => "ICD-10 Disease Category {$code}",
                        'ciri_ciri' => "Gejala spesifik kategori {$code}",
                        'keterangan' => "Klasifikasi Kode {$code}",
                        'kd_ktg' => 'K' . str_pad(rand(1, 11), 2, '0', STR_PAD_LEFT),
                        'status' => rand(0, 1) ? 'Menular' : 'Tidak Menular',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Chunk insert to avoid database parameter limits
        $chunks = array_chunk($allPenyakit, 100);
        foreach ($chunks as $chunk) {
            Penyakit::insert($chunk);
        }
    }
}
