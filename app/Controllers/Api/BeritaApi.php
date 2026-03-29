// app/Controllers/Api/BeritaApi.php
namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Models\BeritaModel;

class BeritaApi extends BaseController
{
use ResponseTrait;

protected $beritaModel;
protected $perPage = 6; // Number of older posts per page

public function __construct()
{
$this->beritaModel = new BeritaModel();
}

// Get latest posts (full width)
public function latest()
{
$latestPosts = $this->beritaModel
->orderBy('tanggal', 'DESC')
->findAll(1); // Get only the latest post

return $this->respond($latestPosts);
}

// Get older posts (half width) with pagination
public function older()
{
$page = $this->request->getVar('page') ?? 1;

// Get total count
$total = $this->beritaModel
->where('id !=', function($builder) {
$builder->selectMax('id')->from('berita');
})
->countAllResults();

$olderPosts = $this->beritaModel
->where('id !=', function($builder) {
$builder->selectMax('id')->from('berita');
})
->orderBy('tanggal', 'DESC')
->paginate($this->perPage, 'default', $page);

return $this->respond([
'posts' => $olderPosts,
'totalPages' => ceil($total / $this->perPage),
'currentPage' => $page
]);
}

// Get categories
public function kategori()
{
$kategori = $this->beritaModel
->select('kategori')
->groupBy('kategori')
->findAll();

return $this->respond($kategori);
}
}