<?php
// controllers/DashboardController.php
require_once './Models/DashboardModel.php';

class DashboardController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->model = new DashboardModel();
    }

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['username'])) {
            http_response_code(404);
            include __DIR__ . '/../views/errors/404.php';
            exit();
        }

        // Profit/loss calculations
        $profit_loss = $this->model->getProfit_Loss();

        $todayProfit = 0;
        $todayExpense = 0;
        $todayNewClientSales = 0;
        $today = date('Y-m-d');
        if (is_array($profit_loss) && !empty($profit_loss)) {
            foreach ($profit_loss as $record) {
                if (isset($record['Sale_Date']) && $record['Sale_Date'] === $today) {
                    $todayProfit += floatval($record['Profit_Loss']);
                    if (isset($record['Result_Type']) && $record['Result_Type'] === 'Loss') {
                        $todayExpense += floatval($record['Profit_Loss']);
                    }
                    if (isset($record['Client_Type']) && $record['Client_Type'] === 'New') {
                        $todayNewClientSales += floatval($record['Profit_Loss']);
                    }
                }
            }
        }
        $todayTotalSales = $todayProfit;
        $todayIncoming = $todayTotalSales ? (floatval($todayProfit - $todayExpense) / $todayTotalSales * 100) : 0;

        error_log("Today Profit: $todayProfit, Expense: $todayExpense, New Client Sales: $todayNewClientSales, Incoming: $todayIncoming%");

        // Inventory data
        $inventoryItems = $this->model->getInventoryItems();
        $totalInventoryValue = $this->model->getTotalInventoryValue();
        $maxExpense = $this->model->getMaxExpense();
        $lowStockItems = $this->model->getLowStockItems();
        $inventoryCount = $this->model->getInventoryCount();

        // Reports data
        $reports = $this->model->getAllReports();
        $totalReportsSales = $this->model->getTotalReportsSales();
        $todayReports = $this->model->getReportsByDate(date('Y-m-d'));
        $productSalesSummary = $this->model->getProductSalesSummary();
        $lowQuantityReports = $this->model->getLowQuantityReports();

        // Stock status for initial load
        $stockStatus = $this->model->getStockStatus();

        // Cart data
        $cart = $this->model->getCart();
        $cartTotal = $this->model->getCartTotal();

        // Inventory items for compatibility
        $tracking = $this->model->getInventoryItems();

        $this->views('dashboards/list', [
            'Profit_Loss' => $profit_loss,
            'Today_Profit' => $todayProfit,
            'Today_Expense' => $todayExpense,
            'Today_New_Client_Sales' => $todayNewClientSales,
            'Today_Incoming' => $todayIncoming,
            'Inventory_Items' => $inventoryItems,
            'Total_Inventory_Value' => $totalInventoryValue,
            'Max_Expense' => $maxExpense,
            'Low_Stock_Items' => $lowStockItems,
            'Inventory_Count' => $inventoryCount,
            'Reports' => $reports,
            'Total_Reports_Sales' => $totalReportsSales,
            'Today_Reports' => $todayReports,
            'Product_Sales_Summary' => $productSalesSummary,
            'Low_Quantity_Reports' => $lowQuantityReports,
            'Stock_Status' => $stockStatus,
            'tracking' => $tracking,
            'Cart' => $cart,
            'Cart_Total' => $cartTotal
        ]);
    }

    public function get_data()
    {
        header('Content-Type: application/json');
        $profitLossData = $this->model->getProfit_Loss();
        $period = isset($_GET['period']) ? $_GET['period'] : 'today';
        $today = date('Y-m-d');
        $startDate = $today;
        $endDate = $today;

        if ($period === 'this_week') {
            $startDate = date('Y-m-d', strtotime('monday this week'));
            $endDate = date('Y-m-d', strtotime('sunday this week'));
        } elseif ($period === 'this_month') {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
        }

        $filteredData = array_filter($profitLossData, function ($record) use ($startDate, $endDate) {
            $saleDate = date('Y-m-d', strtotime($record['Sale_Date']));
            return $saleDate >= $startDate && $saleDate <= $endDate;
        });

        $totalProfit = array_reduce($filteredData, function ($sum, $record) {
            return $sum + floatval($record['Profit_Loss']);
        }, 0);

        $totalExpense = array_reduce($filteredData, function ($sum, $record) {
            return $record['Result_Type'] === 'Loss' ? $sum + floatval($record['Profit_Loss']) : $sum;
        }, 0);

        echo json_encode([
            'totalProfit' => number_format($totalProfit, 2, '.', ''),
            'totalExpense' => number_format($totalExpense, 2, '.', ''),
            'records' => array_values($filteredData)
        ]);
    }

    public function get_inventory_data()
    {
        header('Content-Type: application/json');
        $inventoryItems = $this->model->getInventoryItems();
        $totalValue = array_reduce($inventoryItems, function ($sum, $item) {
            return $sum + (floatval($item['quantity']) * floatval($item['amount']));
        }, 0);
        $maxExpense = $this->model->getMaxExpense();

        echo json_encode([
            'totalInventoryValue' => number_format($totalValue, 2, '.', ''),
            'maxExpense' => number_format($maxExpense, 2, '.', ''),
            'inventoryCount' => count($inventoryItems),
            'records' => array_values($inventoryItems)
        ]);
    }

    public function get_stock_status()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        $categoryId = isset($input['category_id']) ? $input['category_id'] : '';

        $stockStatus = $this->model->getStockStatus($categoryId);

        if (isset($stockStatus['error'])) {
            echo json_encode(['error' => $stockStatus['error']]);
            exit;
        }

        echo json_encode($stockStatus);
    }

    public function add_to_cart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

            if ($productId === false || $quantity === false || $quantity <= 0) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid input']);
                exit;
            }

            $product = $this->model->getProductById($productId);
            if (!$product || $product['quantity'] < $quantity) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Product not available or insufficient stock']);
                exit;
            }

            $this->model->addToCart(
                $productId,
                $product['product_name'],
                $product['unit_price'],
                $quantity
            );

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Item added to cart']);
            exit;
        }
    }

    public function update_cart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

            if ($productId === false || $quantity === false || $quantity < 0) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid input']);
                exit;
            }

            $success = $this->model->updateCart($productId, $quantity);
            if ($success) {
                $cartTotal = $this->model->getCartTotal();
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'cart_total' => number_format($cartTotal, 2),
                    'message' => 'Cart updated'
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Failed to update cart']);
            }
            exit;
        }
    }

    public function checkout()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->model->processOrder();
            header('Content-Type: application/json');
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Order processed successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to process order']);
            }
            exit;
        }
    }
}