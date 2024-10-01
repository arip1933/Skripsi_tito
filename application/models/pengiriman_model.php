<?php
class Pengiriman_Model extends CI_Model
{
	var $table  = 'pengiriman';
	var $key  = 'id_pengiriman';
	function __construct()
	{
		parent::__construct();
		$this->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
	}
	public function get_count()
	{
		return $this->db->count_all('detail_pengiriman');
	}
	function getAll($filter = null, $limit = 20, $offset = 0, $orderBy = null, $orderType = null)
	{
		// Inisialisasi variabel
		$where = "";
		$cond = array();

		// Memproses filter jika ada
		if (isset($filter)) {
			if (!empty($filter->keyword)) {
				$keyword = $this->db->escape_str(strtolower($filter->keyword));
				$cond[] = "(lower(pg.id_pengiriman) like '%$keyword%'
                        or lower(b.id_barang) like '%$keyword%'
                        or lower(b.nama) like '%$keyword%'
                        or lower(k.nama) like '%$keyword%'
                        or lower(pg.status) like '%$keyword%'
                        or lower(p.id_pelanggan) like '%$keyword%'
                        or lower(p.nama) like '%$keyword%'
                        or lower(kr.nama) like '%$keyword%'
                        or lower(kr.id_kurir) like '%$keyword%'
                        or lower(k.id_kategori) like '%$keyword%')";
			}

			if (!empty($filter->status)) {
				if (strtolower($filter->status) != "all")
					$cond[] = "(pg.status = '" . $this->db->escape_str(strtolower($filter->status)) . "')";
			}

			if (!empty($filter->from) || !empty($filter->to)) {
				$from = $this->db->escape_str($filter->from);
				$to = $this->db->escape_str($filter->to);
				$cond[] = "(pg.tanggal >= '$from' and pg.tanggal <= '$to')";
			}

			if (!empty($cond))
				$where = "WHERE " . implode(" AND ", $cond);
		}

		// Menangani limit dan offset
		$limitOffset = "LIMIT $offset, $limit";
		if ($limit == 0)
			$limitOffset = "";

		// Menentukan order by dan order type
		if (!$orderBy)
			$orderBy = $this->key;
		if (!$orderType)
			$orderType = "asc";

		// Membuat query
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS pg.*, 
                                    k.nama AS kategori, 
                                    k.keterangan AS kategori_keterangan, 
                                    kr.nama AS kurir, 
                                    p.nama AS pelanggan, 
                                    p.alamat,
                                    group_concat(concat(dp.id_barang,'|',b.nama,'|',k.nama,'|',b.satuan,'|',dp.qty) ORDER BY b.nama SEPARATOR '===') AS barang
                                FROM " . $this->table . " pg
                                LEFT JOIN detail_pengiriman dp ON dp.id_pengiriman = pg.id_pengiriman
                                LEFT JOIN barang b ON b.id_barang = dp.id_barang
                                LEFT JOIN kategori k ON k.id_kategori = b.id_kategori
                                LEFT JOIN kurir kr ON kr.id_kurir = pg.id_kurir
                                LEFT JOIN pelanggan p ON p.id_pelanggan = pg.id_pelanggan
                                $where 
                                GROUP BY pg.id_pengiriman 
                                ORDER BY $orderBy $orderType 
                                $limitOffset");

		// Mengambil hasil query
		$result = $query->result_array();
		$query->free_result();

		// Menghitung total
		$total = $this->db->query('SELECT found_rows() total_row')->row()->total_row;

		return array($result, $total);
	}


	public function get_by($field, $value = "", $obj = false)
	{
		if (!$field)
			$field = $this->key;

		$where = "WHERE $field = '" . $this->db->escape_str(strtolower($value)) . "'";
		$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS pg.*, k.nama AS kategori, k.keterangan AS kategori_keterangan, kr.nama AS kurir, p.nama AS pelanggan, p.alamat,
										group_concat(concat(dp.id_barang,'|',b.nama,'|',k.nama,'|',b.satuan,'|',dp.qty) ORDER BY b.nama SEPARATOR '===') AS barang
										FROM " . $this->table . " pg
										LEFT JOIN detail_pengiriman dp ON dp.id_pengiriman = pg.id_pengiriman
										LEFT JOIN barang b ON b.id_barang = dp.id_barang
										LEFT JOIN kategori k ON k.id_kategori = b.id_kategori
										LEFT JOIN kurir kr ON kr.id_kurir = pg.id_kurir
										LEFT JOIN pelanggan p ON p.id_pelanggan = pg.id_pelanggan
										$where GROUP BY pg.id_pengiriman
									");


		if (!$obj)
			$result = $query->result_array();
		else
			$result = $query->row();

		$query->free_result();

		return $result;
	}

	public function get_barang_by_id_pengiriman($id_pengiriman)
	{
		$query = $this->db->query("SELECT dp.id_barang, b.nama, k.nama as kategori, b.satuan, dp.qty
								   FROM detail_pengiriman dp
								   LEFT JOIN barang b ON dp.id_barang = b.id_barang
								   LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
								   WHERE dp.id_pengiriman = '" . $this->db->escape_str($id_pengiriman) . "'");
		return $query->result_array();
	}


	function remove($id)
	{
		if (!is_array($id))
			$id = array($id);

		$this->db->where_in($this->key, $id)->delete($this->table);
	}

	function save($id = "", $data = array(), $insert_id = false)
	{

		if (!empty($id)) {
			$this->db->where($this->key, $id);
			$this->db->update($this->table, $data);
		} else {
			$this->db->insert($this->table, $data);
		}

		return $this->db->affected_rows();
	}

	public function get_last()
	{
		$query = $this->db->query("SELECT  * FROM " . $this->table . " order by " . $this->key . " desc limit 0,1");
		$result = $query->row();
		$query->free_result();

		return $result;
	}
	function remove_detail($id)
	{
		if (!is_array($id))
			$id = array($id);

		$this->db->where_in($this->key, $id)->delete("detail_pengiriman");
	}

	// function save_detail($data = array())
	// {
	// 	$this->db->insert("detail_pengiriman", $data);
	// 	return $this->db->affected_rows();
	// }
	function save_detail($data = array(), $status)
	{
		// Cek apakah data dengan id_pengiriman dan id_barang sudah ada
		$this->db->where('id_pengiriman', $data['id_pengiriman']);
		$this->db->where('id_barang', $data['id_barang']);
		$query = $this->db->get('detail_pengiriman');

		if ($query->num_rows() > 0) {
			// Jika data sudah ada, update qty
			$existing_data = $query->row();

			if ($status == "3") { // Status Ditolak
				// Jika statusnya ditolak, tambahkan qty ke banyak_barang
				$difference = $existing_data->qty;
				$this->db->set('banyak_barang', 'banyak_barang + ' . $difference, FALSE);
			}else if($status == "4"){
				$difference = $existing_data->qty - $data['qty'] ;
            	$this->db->set('banyak_barang', 'banyak_barang + ' . $difference, FALSE);
			} else {
				// Perhitungan qty berdasarkan status "Dikirim" atau "Diterima"
				if ($existing_data->qty > $data['qty']) {
					// Jika qty di database lebih besar, kurangi qty barang di tabel barang
					$difference = $existing_data->qty - $data['qty'];
					$this->db->set('banyak_barang', 'banyak_barang + ' . $difference, FALSE); // Tambahkan perbedaan ke stok barang
				} else {
					// Jika qty di database lebih kecil, tambah qty barang di tabel barang
					$difference = $data['qty'] - $existing_data->qty;
					$this->db->set('banyak_barang', 'banyak_barang - ' . $difference, FALSE); // Kurangi perbedaan dari stok barang
				}
			}

			// Update tabel barang
			$this->db->where('id_barang', $data['id_barang']);
			$this->db->update('barang');

			// Update detail_pengiriman
			$this->db->where('id_pengiriman', $data['id_pengiriman']);
			$this->db->where('id_barang', $data['id_barang']);
			$this->db->update('detail_pengiriman', array('qty' => $data['qty']));
		} else {
			// Jika data tidak ada, insert data baru
			$this->db->insert('detail_pengiriman', $data);

			// Kurangi qty barang dari stok di tabel barang
			if ($status != "3") { // Jangan kurangi stok jika statusnya "Ditolak"
				$this->db->set('banyak_barang', 'banyak_barang - ' . $data['qty'], FALSE);
				$this->db->where('id_barang', $data['id_barang']);
				$this->db->update('barang');
			}
		}

		return $this->db->affected_rows();
	}



	public function count()
	{
		return $this->db->count_all('detail_pengiriman');
	}

	public function get_qty_by_id_pengiriman($id_pengiriman)
	{
		$this->db->select('qty');
		$this->db->from('detail_pengiriman');
		$this->db->where('id_pengiriman', $id_pengiriman);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row()->qty;
		} else {
			return null; // Jika data tidak ditemukan
		}
	}
}
