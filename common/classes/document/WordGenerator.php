<?php

namespace common\classes\document;


use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Yii;

class WordGenerator implements GeneratorInterface
{
    const BEFORE_QUOTE_CHAR = '${';
    const AFTER_QUOTE_CHAR = '}';

    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var string
     */
    protected $templatePath;

    /**
     * @var array
     */
    protected $templateData;

    /**
     * @var TemplateProcessor
     */
    protected $phpWord;

    /**
     * @var string
     */
    protected $tempDirPath = '@backend/runtime';

    /**
     * @param string $path
     */
    public function setFilePath(string $path)
    {
        $this->filePath = $path;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @param string $path
     */
    public function setTemplatePath(string $path)
    {
        $this->templatePath = $path;
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    /**
     * @param array $data
     */
    public function setTemplateData(array $data)
    {
        $this->templateData = $data;
    }

    /**
     * @return array
     */
    public function getTemplateData(): array
    {
        return $this->templateData;
    }

    /**
     * @return TemplateProcessor
     */
    public function getPhpWord(): TemplateProcessor
    {
        if ($this->phpWord === null) {
            Settings::setTempDir(Yii::getAlias($this->tempDirPath));
            $this->phpWord = new TemplateProcessor($this->getTemplatePath());
        }

        return $this->phpWord;
    }

    /**
     * Генерирует документ
     *
     * @return bool
     */
    public function generate(): bool
    {
        $this->processData();

        return $this->saveFile();
    }

    /**
     * Подстановка данных в шаблон
     */
    protected function processData()
    {
        $phpWord = $this->getPhpWord();
        $data = $this->getFormattedTemplateData();
        foreach ($data as $key => $value) {
            $phpWord->setValue($key, $value);
        }
    }

    /**
     * Подставляется экранирование и проверяется тип
     *
     * @return array
     */
    protected function getFormattedTemplateData(): array
    {
        $result = [];
        $data = $this->getTemplateData();
        foreach ($data as $key => $val) {
            $key = self::BEFORE_QUOTE_CHAR . $key . self::AFTER_QUOTE_CHAR;
            if (!is_array($val)) {
                $result[$key] = $val;
            }
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function saveFile(): bool
    {
        $phpWord = $this->getPhpWord();
        $filePath = $this->getFilePath();
        $phpWord->saveAs($filePath);

        return file_exists($filePath);
    }
}