<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Reports_WizardStatsController extends Controller
{
    /**
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $db;

    public function __construct()
    {
        $this->db = DB::connection();
    }

    public function index()
    {
        return view('reports_wizard_stats.index');
    }

    public function migrations()
    {
        $sql = $this->_getMigrationsQuery();
        $sqlCount = $this->_getMigrationsQuery(true);

        $migrations = [];

        $res = $this->db->select($sql);

        foreach ($res as $row) {
            $row['storesSetupTime'] = $row['storesSetupTime'] ? $row['storesSetupTime'] + 2 : 0;
            $migrations[] = [
                'migrationId' => $row['migrationId'],
                'wizardCreated' => $row['wizardCreated'],
                'demoCompleted' => $row['demoCompleted'],
                'fullCompleted' => $row['fullCompleted'],
                'sourceId' => $row['sourceId'],
                'sourceUsedPlugin' => $this->_prepareTime($row['sourceUsedPlugin']),
                'targetId' => $row['targetId'],
                'targetUsedPlugin' => $this->_prepareTime($row['targetUsedPlugin']),
                'price' => $row['price'],
                'estimatorPrice' => $row['estimatorPrice'],
                'lastLoginDate' => $row['lastLoginDate'],
                'demoRate' => $row['demoRate'],
                'demoResultsChecked' => $row['demoResultsChecked'],
                'storesSetupTime' => (int)($row['storesSetupTime'] / 60) . 'm. ' . ($row['storesSetupTime'] % 60) . 's',
                'qualityProcessTime' => $this->_prepareTime($row['qualityProcessTime']),
            ];
        }

        $migrationsCount = $this->db->select($sqlCount)->value('count');

        return response()->json([
            'migrations' => $migrations,
            'migrationsCount' => $migrationsCount,
        ]);
    }

    private function _getMigrationsQuery($count = false, $start = null, $limit = null, $allData = false)
    {
        $forceLimit = true;
        if ($start === null) {
            $start = (int) request()->input('start', 0);
        }

        if ($limit === null) {
            $forceLimit = false;
            $limit = (int) request()->input('limit', 15);
        }

        $filterData = $this->_getFilterData();

        $useIds = !$count;
        foreach ($filterData as $filter) {
            if ($filter['field'] == 'migrationId') {
                $useIds = false;
                break;
            }
        }

        if (!$allData && $useIds) {
            $migrationsSelect = $this->db->table('migrations')
                ->select('id')
                ->orderBy('id', 'desc')
                ->limit($limit, $start);

            $useIds = $migrationsSelect->pluck('id')->all();
        }

        $filter = new \Cart2cart\Extjs4\Filter([
            'migrationId' => 'm.id',
            'price' => 'm.price_in_dollars_with_discount',
            'estimatorPrice' => 'm.price_in_dollars',
            'totalVariants' => 'm.total_variants',
            'processedVariants' => 'm.processed_variants',
            'status' => 'm.status',
            'createdAt' => 'm.created_at',
            'updatedAt' => 'm.updated_at',
        ]);

        $query = Migration::find()
            ->alias('m')
            ->select($filter->getSelect())
            ->andWhere(['m.owner_id' => Yii::$app->user->id]);

        if ($filter->hasFilters()) {
            $query->andWhere($filter->getFilters());
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->pageSize,
            ],
            'sort' => [
                'defaultOrder' => ['createdAt' => SORT_DESC],
            ],
        ]);

        return $dataProvider;
    }
}