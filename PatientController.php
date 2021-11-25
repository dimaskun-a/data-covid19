<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
	public function __construct()
	{
		// memanggil model patient
		$this->patientsModel = new Patient();
	}

	public function index()
	{
		// METHOD untuk menampilkan seluruh data pasien

		// mendapatkan semua data
		$patient = $this->patientModel->allData();

		// check apakah terdapat pasien di array
		$patientNotExist = count($patient) == 0;

		// kondisi ketika pasien tidak ada, maka memunculkan response gagal menampilkan karena data pasien kosong
		if ($patientNotExist) {
			return $this->patientModel->responseFail('Data is empty', 200);
		}

		// kondisi ketika pasien ada, maka memunculkan response sukses menampilkan semua data pasien
		return $this->patientModel->responseSuccess($patient, 'Get all resource');
    }
	public function store(Request $request)
	{
		// METHOD untuk menambahkan data pasien

		// kondisi ketika kolom status_id kurang dari atau sama dengan 3 (status exist)
		if ($request->status_id <= 3) {
			$positive = 1;
			// validasi rules
			$rules = [
				'name' => 'required',
				'phone' => 'required',
				'address' => 'required',
				'status_id' => 'required',
				'in_date_at' => 'required',
				'out_date_at' => 'required',
			];

			// kondisi ketika status pasien bukan positif maka akan mengambil semua input
			if ($request->status_id !== $positive) {
				return $this->storePatientByRules($request->all(), $rules);
			}

			// kondisi ketika pasien positif 

			$outDateAt = 5;
			$justRemoveThis = 1;

			// hapus out_date_at pada array rules
			array_splice($rules, $outDateAt, $justRemoveThis); 

			// input yang dihasilan ketika pasien positif
			// dan memaksa out_date_at bernilai null
			$createData = [
				'name' => $request->name,
				'phone' => $request->phone,
				'address' => $request->address,
				'status_id' => $request->status_id,
				'in_date_at' => $request->in_date_at,
				'out_date_at' => null,
			];

			return $this->storePatientByRules($createData, $rules);
		}

		// kondisi status_id lebih dari 3 (status didnt exist)
		// maka akan memunculkan response gagal menambahkan data
		return $this->patientModel->responseFail(
			'cannot insert more than 4 value in status_id. Try, 1 = Positive, 2 = Recovered, 3 = Dead.',
			412
		);
	}

	public function show($id)
	{
		// METHOD untuk menampilkan detail data pasien
		
		// mencari pasien berdasarkan id
		$patient = $this->patientModel->findById($id);
		// $patient = Patient::find($id);
		// check apakah pasien tidak ada
		$patientNotExist = $patient == null;

		// kondisi pasien tidak ada, akan memunculkan response gagal  memunculkan detail data pasien
		if ($patientNotExist) {
			return $this->patientModel->responseFail();
		}

		// kondisi pasien ada, akan memunculkan response sukses memunculkan detail data pasien
		return $this->patientModel->responseSuccess($patient, 'Get detail resource');
	}

	public function update(Request $request, $id)
	{
		// METHOD untuk mengupdate/edit data pasien

		// mencari pasien berdasarkan id
		$patient = Patient::find($id);
		// check pasien apakah tidak ada
		$patientNotExist = $patient == null;

		// kondisi pasien tidak ada, akan memunculkan response gagal meng-update data pasien
		if ($patientNotExist) {
			return $this->patientModel->responseFail();
		}

		// kondisi pasien ada, akan memunculkan response sukses meng-update data pasien
		return $this->patientModel->responseSuccess(
			$patient->update($request->all()),
			'Resource is update successfully'
		);
	}

	public function destroy($id)
	{
		// METHOD untuk menghapus data pasien
		
		// mencari pasien berdasarkan id
		$patient = Patient::find($id);
		// check apakah pasien tidak ada
		$patientNotExist = $patient == null;

		// kondisi pasien tidak ada, akan memunculkan response gagal menghapus data pasien
		if ($patientNotExist) {
			return $this->patientModel->responseFail();
		}

		// kondisi pasien ada, akan memunculkan response succes menghapus data pasien
		return $this->patientModel->responseSuccess(
			$patient->delete(), 
			'Resource is delete successfully'
		);
	}   

	public function search($name)
	{
		// METHOD untuk mencari pasien berdasarkan nama

		// cari pasien berdasarkan nama
		$patient = $this->patientModel->findByName($name);
		// check apakah pasien tidak ada
		$patientNotExist = count($patient) == 0;

		// kondisi ketika pasien tidak ada, maka memunculkan response gagal 
		// menampilkan pasien yang dicari berdasakan nama 
		if ($patientNotExist) {
			return $this->patientModel->responseFail();
		}

		// kondisi pasien ada, akan memunculkan response sukses dan 
		// menampilkan pasien yang dicari berdasarkan nama
		return $this->patientModel->responseSuccess($patient, 'Searched Resource');
	}

	public function positive() 
	{
		// METHOD untuk mencari pasien dengan status positif

		$positive = 1;
		// mencari pasien berdasarkan status positif
		$patient = $this->patientModel->findByStatus($positive);
		// $patient = Patient::where('status_id', $positive)->get();
		// check apakah pasien tidak ada
		$patientNotExist = count($patient) == 0;

		// kondisi pasien tidak ada, akan memunculkan response gagal
		// menampilkan pasien dengan status positif
		if ($patientNotExist) {
			return $this->patientModel->responseFail();
		}

		// kondisi pasien ada, akan memunculkan response sukses
		// menampilakna pasien dengan status positif
		return $this->patientModel->responseSuccess($patient, 'Get positive resource');
	}

	public function recovered()
	{
		// METHOD untuk mencari pasien dengan status recovered

		$recovered = 2;
		// mencari pasien dengan status recovered
		$patient = $this->patientModel->findByStatus($recovered);
		// $patient = Patient::where('status_id', $recovered)->get();
		// check apakah pasien tidak ada
		$patientNotExist = count($patients) == 0;


		// kondisi pasien tidak ada, akan memunculkan response gagal
		// menampilkan pasien dengan status recovered
		if ($patientNotExist) {
			return $this->patientModel->responseFail();
		}

		// kondisi pasien ada, akan memunculkan response sukses
		// menampilkan pasien dengan status recovered
		return $this->patientModel->responseSuccess($patient, 'Get recovered resource');
	}

	public function dead()
	{
		// METHOD untuk mencari pasien dengan status dead

		$dead = 3;
		// mencari pasien berdasarkan status dead
		$patient = $this->patientModel->findByStatus($dead);
		// $patient = Patient::where('status_id', $dead)->get();
		// check apakah pasien tidak ada
		$patientNotExist = count($patient) == 0;

		// kondisi pasien tidak ada, akan memunculkan response gagal
		// menampilkan pasien dengan status dead
		if ($patientNotExist) {
			return $this->patientModel->responseFail();
		}

		// kondisi ketika pasien ada, maka memunculkan response sukses
		// menampilkan pasien dengan status dead
		return $this->patientModel->responseSuccess($patient, 'Get dead resource');
	}

	private function storePatientByRules($data, $rules)
	{
		// METHOD menambahkan pasien berdasarkan rules

		// validasi input apakah sesuai dengan rules
		$validator = Validator::make($data, $rules);

		// kondisi ketika validasi gagal maka memunculkan response gagal
		// menambahkan pasien
		if ($validator->fails()) {
			return $this->patientModel->responseFail(
				'name, phone, address, status, in_date_at, out_date_at must be inserted',
				412
			);
		}

		// kondisi validasi sukses akan memunculkan response sukses
		// dan data sukes ditambahkan
		$patient = Patient::create($data);
		return $this->patientModel->responseSuccess(
			$patient, 
			'Resource is added successfully', 
			201
		);
	}
}