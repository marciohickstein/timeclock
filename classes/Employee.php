<?php
class Employee
{
    protected $sql;
    protected $id;
    protected $name;
    protected $occupation;
    protected $errorLoadData;

    public function __construct()
    {
        $this->sql = new Sql();
    }

    public function __toString()
    {
        return array(
                "id" => $this->getId(),
                "name" => $this->getName(),
                "occupation" => $this->getOccupation()
                );
    }

    /**
     * Get the value of error
     */
    public function getErrorMessage()
    {
        return $this->sql->getErrorMessage();
    }

    /**
     * Get the value of occupation
     */
    public function getOccupation()
    {
        return $this->occupation;
    }

    /**
     * Set the value of occupation
     *
     * @return  self
     */
    public function setOccupation($occupation)
    {
        $this->occupation = $occupation;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the employee and load attributes
     */
    public function loadById($id)
    {
        if (!empty($id)) {
            $params = array(":ID" => $id);
            $row = $this->sql->select("SELECT * FROM employee WHERE id=:ID", $params);
            if (count($row) == 1) {
                $this->setId($row[0]['id']);
                $this->setName($row[0]['name']);
                $this->setOccupation($row[0]['occupation']);
                return true;
            }
        }
        return false;
    }

    /**
     * Get the employee and load attributes
     */
    public function deleteById($id)
    {
        if (!empty($id)) {
            $params = array(":ID" => $id);
            if ($this->sql->query("DELETE FROM employee WHERE id=:ID", $params) != null)
                return (bool)true;
        }
        return (bool)false;
    }

    /**
     * Get the employee and load attributes
     */
    public function getList()
    {
        return $this->sql->select("SELECT * FROM employee", array());
    }

    public function setData($data)
    {
        // Valida os campos enviados
        $id = $data['id'];
        if (!isset($id) && !is_numeric($id))
        {
            $this->setErrorLoadData("Nao foi possivel carregar o campo de identificacao (ID)");
            return false;
        }

        $name = $data['name'];
        if (!isset($name) || empty($name))
        {
            $this->setErrorLoadData("Nao foi possivel carregar o campo de nome (NAME)");
            return false;
        }

        $occupation = $data['occupation'];
        if (!isset($occupation) || empty($occupation))
        {
            $this->setErrorLoadData("Nao foi possivel carregar o campo de cargo (CARGO)");
            return false;
        }
        
        $this->id = $id;
        $this->name = $name;
        $this->occupation = $occupation;

        return true;
    }

    public function insert(){
        $params = array(":ID" => $this->getId(), ":NOME" => $this->getName(), ":OCCUPATION" => $this->getOccupation());
        return ($this->sql->query("INSERT INTO employee (id, name, occupation) VALUES (:ID, :NOME, :OCCUPATION)", $params));
    }

    /**
     * Get the value of errorLoadData
     */ 
    public function getErrorLoadData()
    {
        return $this->errorLoadData;
    }

    /**
     * Set the value of errorLoadData
     *
     * @return  self
     */ 
    public function setErrorLoadData($errorLoadData)
    {
        $this->errorLoadData = $errorLoadData;

        return $this;
    }
}
?>