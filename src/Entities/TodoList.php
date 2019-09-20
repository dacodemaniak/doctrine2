<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Entities\Task;

/**
 * @ORM\Entity
 * @ORM\Table(name="todolist")
 * 
 * @author jean-luc
 *
 */
class TodoList
{

    /**
     * Primary key of one Todolist
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * Title of one TodoList
     * @var string
     *
     * @ORM\Column(type="string", name="title")
     */
    private $title;
    
    /**
     * Collection of Tasks of this TodoList
     * @var ArrayCollection
     */
    private $tasks;
    
    public function __construct() {
        $this->tasks = new ArrayCollection();
    }
    
    /**
     * Returns the id value of a TodoList
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }
    
    /**
     * Returns the title of this TodoList
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }
    
    /**
     * Sets the title of this TodoList
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self {
        $this->title = $title;
        
        return $this;
    }
    
    public function getTasks(): ArrayCollection {
        return $this->tasks;
    }
    
    public function addTask(Task $task): self {
        $this->tasks->add($task);
        return $this;
    }
    
    public function removeTask(Task $task): self {
        $this->tasks->removeElement($task);
        return $this;
    }
}

