<?php
namespace Cac\Support;

class File
{
    protected $file;
    protected $path;
    protected $maxSize;
    protected $mimeType =  [];
    protected $errors   =  [];

    public function __construct($file,$path)
    {
        $this->file = $file;
        $this->path = $path;
    }

    public function setName($name)
    {
        $this->file['name'] = $name.".{$this->getInfo()->getExtension()}";
        return $this;
    }

    public function maxSize($size)
    {
        $this->maxSize = $size;
        return $this;
    }

    public function mimeType($type)
    {
        $this->mimeType = array_merge($this->mimeType, $type);
        return $this;
    }

    public function getInfo()
    {
        $info = new \SplFileInfo($this->file['name']);
        return $info;
    }

    public function getData()
    {
        $data = new \stdClass();

        $data->temp      = $this->file['tmp_name'];
        $data->name      = $this->file['name'];
        $data->extension = $this->getInfo()->getExtension();
        $data->size      = $this->file['size'];
        $data->mime      = $this->file['type'];

        return $data;
    }


    public function upload()
    {
        $this->valid();

        if(!empty($this->errors))
        {
            throw new \RuntimeException('Errors');
        }

        if(move_uploaded_file($this->file['tmp_name'], $this->path.'/'.$this->file['name']))
        {

        } else
        {
            $this->setErrors('Ocorreu um erro ao enviar o arquivo para a pasta correta');
            echo $this->getErrors();
        }
    }

    public function setErrors($error)
    {
        $this->errors[] = $error;
        return $this;

    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function valid()
    {
        if ($this->getData()->size > $this->convertToBytes($this->maxSize))
        {
            $this->setErrors('Exceeded filesize limit.');
        }

        if(!in_array($this->getData()->mime,$this->mimeType))
        {
            $this->setErrors('Extension Invalid');
        }
        return $this;
    }

    private function convertToBytes($from){
        $number=substr($from,0,-2);
        switch(strtoupper(substr($from,-2))){
            case "KB":
                return $number*1024;
            case "MB":
                return $number*pow(1024,2);
            case "GB":
                return $number*pow(1024,3);
            case "TB":
                return $number*pow(1024,4);
            case "PB":
                return $number*pow(1024,5);
            default:
                return $from;
        }
    }
}

