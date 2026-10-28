<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Models\Reports;
use App\Models\User;
use RuntimeException;

final class ReportsController extends Controller
{
    private User $users;

    public function __construct(private Reports $reports, \PDO $database)
    {
        $this->users = new User($database);
    }

    public function index(): void
    {
        if (!$this->ensureAdmin()) return;
        $this->view('admin/reports/index', ['title' => 'Panel de empresa'] + $this->reports->dashboard());
    }

    public function generate(): void
    {
        if (!$this->ensureAdmin()) return;

        $type = (string) ($_GET['type'] ?? 'sales');
        if (!in_array($type, ['sales', 'orders', 'inventory'], true)) $type = 'sales';
        $dateFrom = (string) ($_GET['from'] ?? date('Y-m-01'));
        $dateTo = (string) ($_GET['to'] ?? date('Y-m-d'));
        $dateError = '';

        foreach ([$dateFrom, $dateTo] as $date) {
            $parsedDate = \DateTimeImmutable::createFromFormat('!Y-m-d', $date);
            if (!$parsedDate || $parsedDate->format('Y-m-d') !== $date) {
                $dateError = 'Selecciona un rango de fechas valido.';
                break;
            }
        }
        if ($dateError === '' && $dateFrom > $dateTo) {
            $dateError = 'La fecha inicial no puede ser posterior a la fecha final.';
        }

        $report = $dateError === '' ? $this->reports->generateReport($type, $dateFrom, $dateTo) : null;
        $this->view('admin/reports/generate', [
            'title' => 'Reportes',
            'reportType' => $type,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'report' => $report,
            'dateError' => $dateError,
        ]);
    }

    public function updateOrderStatus(): void
    {
        if (!$this->ensureAdmin()) return;
        Csrf::validate($_POST['_csrf'] ?? null);

        try {
            $this->reports->updateOrderStatus(
                (int) ($_POST['order_id'] ?? 0),
                trim((string) ($_POST['status_code'] ?? '')),
                (int) Auth::userId()
            );
            $this->flash('Estado del pedido actualizado.');
        } catch (RuntimeException $exception) {
            $this->flash($exception->getMessage(), 'error');
        }

        $this->redirect('/SS_NOVA_TECHNOLOGY/public/admin/reports');
    }

    private function ensureAdmin(): bool
    {
        if (!Auth::check()) $this->redirect('/SS_NOVA_TECHNOLOGY/public/login');
        if (!Auth::userId() || !$this->users->isAdmin(Auth::userId())) {
            http_response_code(403);
            echo 'No tienes permisos para acceder a esta seccion.';
            return false;
        }
        return true;
    }
}