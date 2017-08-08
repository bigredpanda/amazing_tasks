<?php

namespace App\Controller;

use App\Model\Task;
use Core\Config;
use Core\Controller;
use Core\Session;
use Exception;
use Lib\ImageUploader;


/**
 * Class TaskController
 * @package App\Controller
 * @author Pavel Spichak
 */
class TaskController extends Controller
{
    private $taskModel;

    function __construct()
    {
        parent::__construct();

        $this->taskModel = new Task();
    }


    function indexAction(): string
    {
        $orderField = (isset($_GET['order_field'])) ? $_GET['order_field'] : 'title';
        $orderDirection = (isset($_GET['order_direction'])) ? $_GET['order_direction'] : 'asc';


        $currentPage = (isset($_GET['page'])) ? $_GET['page'] - 1 : 0;
        $tasksCount = $this->taskModel->getTasksCount();
        $tasksCountOnPage = 3;
        $pageCount = ceil($tasksCount / $tasksCountOnPage);
        $start = abs($currentPage * $tasksCountOnPage);

        $tasks = $this->taskModel->getList($orderField, $orderDirection, $start, $tasksCountOnPage);

        $isCanEdit = false;
        $currentUser = $this->getUser();
        if ($currentUser) {
            $isCanEdit = in_array('admin', $currentUser['roles']);
        }

        return $this->twig->render('Task/list.html.twig', [
            'tasks' => $tasks,
            'order' => [
                'field'     => $orderField,
                'direction' => $orderDirection
            ],

            'order_fields' => [
                'title'  => 'title',
                'author' => 'author',
                'email'  => 'email',
                'status' => 'is_performed'
            ],

            'order_directions' => ['asc', 'desc'],
            'current_page'     => $currentPage + 1,
            'page_count'       => $pageCount,
            'is_can_edit'      => $isCanEdit,
            'tasks_count'      => $tasksCount
        ]);
    }


    function createAction(): string
    {
        $result = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $data = $_POST;
                $data['image'] = ImageUploader::uploadImage($_FILES);
                $isCreated = $this->taskModel->create($data);

                if (!$isCreated && isset($filePath)) {
                    unlink($filePath);
                    Session::set('flash', [
                        'type'    => 'danger',
                        'message' => 'Unknown error! Task was not created!'
                    ]);
                } else {
                    Session::set('flash', [
                        'type'    => 'success',
                        'message' => 'Task has been successfully created!'
                    ]);
                }

                header("Location: " . '/');
            } catch (Exception $e) {
                $result = [
                    'error'   => true,
                    'message' => $e->getMessage()
                ];

                if (isset($filePath)) {
                    unlink($filePath);
                }
            }
        }

        return $this->twig->render('Task/create.html.twig', $result);
    }


    function updateAction(int $id): string
    {
        $result = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $data = $_POST;
                $data['is_performed'] = (int)isset($_POST['is_performed']);
                $isUpdated = $this->taskModel->update($id, $data);

                if ($isUpdated) {
                    Session::set('flash', [
                        'type'    => 'success',
                        'message' => 'Task has been successfully updated!'
                    ]);
                } else {
                    Session::set('flash', [
                        'type'    => 'danger',
                        'message' => 'Unknown error! Task was not updated!'
                    ]);
                }
                header("Location: " . '/');
            } catch (Exception $e) {
                $result = [
                    'error'   => true,
                    'message' => $e->getMessage()
                ];
            }
        } else {
            $task = $this->taskModel->getById($id);
            if (!$task) {
                Session::set('flash', [
                    'type'    => 'danger',
                    'message' => 'No task with this id'
                ]);
                header("Location: " . '/');
            }
            $result['task'] = $task;
        }

        return $this->twig->render('Task/edit.html.twig', $result);
    }


    public function removeAction(int $id): string
    {
        $result = [
            'error' => false
        ];

        try {
            $imageName = $this->taskModel->getById($id, 'image');
            if (!$this->taskModel->delete($id)) {
                $result['error'] = true;
            } else {
                $imagePath = Config::get('image_upload_directory') . $imageName;
                unlink($imagePath);
            }
        } catch (Exception $e) {
            $result = [
                'error' => true,
            ];
        }

        return json_encode($result);
    }

}