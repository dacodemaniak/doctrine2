<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Entities\TodoList as TodoList;

/**
 * @ORM\Entity
 * @ORM\Table(name="task")
 * 
 * @author jean-luc
 *
 */
class Task
{
    /**
     * Primary key of this Task
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * Description of this Task
     * @var string
     *
     * @ORM\Column(type="string", name="description")
     */
    private $description;

    /**
     * Beginning date of this Task
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="begin_date")
     */
    private $beginAt;
    
    /**
     * @ORM\ManyToOne(targetEntity=TodoList::class, cascade={"persist"}, inversedBy="tasks")
     *
     * @var TodoList
     */
    private $todoList;
    
    public function __construct(){}
    
    public function getId(): int {
        return $this->id;
    }
    
    public function description(string $description = null) {
        if ($description === null) {
            return $this->description;
        }
        
        $this->description = $description;
        
        return $this;
    }
    
    public function beginAt(\DateTime $beginAt = null) {
        if ($beginAt === null) {
            return $this->beginAt;
        }
        
        $this->beginAt = $beginAt;
        
        return $this;
    }
    
    public function todoList(TodoList $todoList = null) {
        if ($todoList === null) {
            return $this->todoList;
        }
        $this->todoList = $this->todoList;
        
        return $this;
    }
}

