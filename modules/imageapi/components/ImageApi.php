<?php
namespace app\modules\imageapi\components;

use Yii;
use yii\base\Component;

class ImageApi extends Component
{

    public $presets = array();
    public $cacheIn;
    public $allowedImageExtensions = ['*.*'];
    public $controller = 'site';
    public $error = '';

    static private $_matrix = null;

    public function init()
    {
        parent::init();
        self::$_matrix = [
            "й" => "i",
            "ц" => "c",
            "у" => "u",
            "к" => "k",
            "е" => "e",
            "н" => "n",
            "г" => "g",
            "ш" => "sh",
            "щ" => "shch",
            "з" => "z",
            "х" => "h",
            "ъ" => "",
            "ф" => "f",
            "ы" => "y",
            "в" => "v",
            "а" => "a",
            "п" => "p",
            "р" => "r",
            "о" => "o",
            "л" => "l",
            "д" => "d",
            "ж" => "zh",
            "э" => "e",
            "ё" => "e",
            "я" => "ya",
            "ч" => "ch",
            "с" => "s",
            "м" => "m",
            "и" => "i",
            "т" => "t",
            "ь" => "",
            "б" => "b",
            "ю" => "yu",
            "Й" => "I",
            "Ц" => "C",
            "У" => "U",
            "К" => "K",
            "Е" => "E",
            "Н" => "N",
            "Г" => "G",
            "Ш" => "SH",
            "Щ" => "SHCH",
            "З" => "Z",
            "Х" => "X",
            "Ъ" => "",
            "Ф" => "F",
            "Ы" => "Y",
            "В" => "V",
            "А" => "A",
            "П" => "P",
            "Р" => "R",
            "О" => "O",
            "Л" => "L",
            "Д" => "D",
            "Ж" => "ZH",
            "Э" => "E",
            "Ё" => "E",
            "Я" => "YA",
            "Ч" => "CH",
            "С" => "S",
            "М" => "M",
            "И" => "I",
            "Т" => "T",
            "Ь" => "",
            "Б" => "B",
            "Ю" => "YU",
            "«" => "",
            "»" => "",
            " " => "-",
            "\"" => "",
            "\." => "",
            "–" => "-",
            "\," => "",
            "\(" => "",
            "\)" => "",
            "\?" => "",
            "\!" => "",
            "\:" => "",
            '#' => '',
            '№' => '',
            ' - ' => '-',
            '/' => '-',
            '  ' => '-',
        ];
    }

    /**
     * Basic function for create a derived image from a file using a preset declared in
     * configuration
     *
     * @param $presetName Name of the preset declared in configuration under presets array
     * in the way:
     * <pre>
     *    '640x480'=>array(
     *      'cacheIn'=>'webroot.repository.640x480',
     *      'actions'=>array( 'scaleAndCrop'=>array('width'=>640, 'height'=>480) ),
     *    ),
     * </pre>
     * @param $file origin image file to create the derived image
     *
     * @return the path to the cached derived image (if it does not exist it'll be generated
     *   transparently)
     */
    public function createPath($presetName, $file, $only_return_path = false)
    {

        if (!isset($this->presets[$presetName])) {
            return false;
        }

        if (!file_exists($file)) {
            return false;
        }

        $preset = $this->presets[$presetName];

        if (isset($preset)) {

            $basename = basename($file);
            $targetPath = Yii::getAlias($preset['cacheIn']);
            $targetFile = $targetPath . '/' . strtolower($basename);

            if (!file_exists($targetPath)) {
                mkdir($targetPath, 0777, true);
            }

            if ($only_return_path || file_exists($targetFile)) {

                return $targetFile;

            } else {

                if (isset($preset['actions'])) {

                    // Не увеличивать
                    if (isset($preset['actions']['image_increase']) && $preset['actions']['image_increase'] === false) {
                        $size = self::getSize($file);
                        if ($size[0] < $preset['actions']['image_x'] || $size[1] < $preset['actions']['image_y']) {
                            $preset['actions']['image_resize'] = false;
                        }
                    }

                    $this->createHandle($preset, $file, $targetPath);

                } else {

                    copy($file, $targetFile);

                }

                if (file_exists($targetFile)) {

                    chmod($targetFile, 0666);

                    return $targetFile;

                } else {

                    return false;

                }
            }
        } else {
            return false;
        }
    }

    protected function createHandle($preset, $srcPath, $targetPath = '')
    {

        $handle = \Yii::createObject([
          'class' => 'app\modules\imageapi\helpers\upload'
        ], [['file' => $srcPath]]);

        $handle->file_safe_name = false;
        $handle->file_overwrite = true;
        $handle->file_auto_rename = false;

        if (isset($preset['actions']['image_watermark_path']) && isset($preset['actions']['image_watermark'])) {

            $preset['actions']['image_watermark'] = Yii::getAlias($preset['actions']['image_watermark_path']) . DIRECTORY_SEPARATOR . $preset['actions']['image_watermark'];

        }

        if (isset($preset['actions'])) {
            foreach ($preset['actions'] as $action => $params) {

                $handle->$action = $params;

            }
        }

        if ($targetPath) {

            $handle->process($targetPath);

            if ($handle->processed) {

                $handle->file_dst_pathname;

            } else {

                $this->error = $handle->error;

            }

        } else {

            return $handle;

        }

    }

    /**
     * Basic function for create a derived image from a file using a preset declared in
     * configuration
     *
     * @param $presetName Name of the preset declared in configuration under presets array
     * in the way:
     * <pre>
     *    '640x480'=>array(
     *      'cacheIn'=>'webroot.repository.640x480',
     *      'actions'=>array( 'scaleAndCrop'=>array('width'=>640, 'height'=>480) ),
     *    ),
     * </pre>
     * @param $file origin image file to create the derived image
     *
     * @return the URL to the cached derived image (if it does not exist it'll be generated
     *   transparently)
     */
    public function createUrl($presetName = 'original', $file)
    {

        $webrootPath = Yii::getAlias('@webroot');

        if (!file_exists($file)) {
            $file = $webrootPath . $file;
        }

        $targetPath = $this->createPath($presetName, $file);

        if (strpos($targetPath, $webrootPath) !== false) {
            $targetPath = substr($targetPath, strlen($webrootPath));
        }

        return $targetPath;
    }

    public function getImage($presetName = 'original', $file, $inline = false)
    {

        if (!isset($this->presets[$presetName])) {
            return false;
        }

        if (!file_exists($file)) {
            return false;
        }

        $preset = $this->presets[$presetName];

        $handle = $this->createHandle($preset, $file);
        header('Content-type: ' . $handle->file_src_mime);
        header("Content-Disposition: " . ($inline ? 'inline' : 'attachment') . "; filename=" . rawurlencode($handle->file_src_name) . ";");

        echo $handle->process();

        $this->error = $handle->error;

    }

    public function createImage($source, $file_name)
    {
        if (!file_exists($source)) {
            return false;
        }

        $preset = $this->presets['original'];

        if (!isset($preset['cacheIn'])) {

            return false;

        }

        $file_info = pathinfo($file_name);

        if (!(isset($file_info['filename']) && isset($file_info['extension']))) {
            return false;
        }

        $file_name = self::cyrillicToLatin($file_info['filename']);
        $file_name = str_replace(array(' ', '-'), array('_', '_'), $file_name);
        $file_name = preg_replace('/[^A-Za-z0-9_]/', '', $file_name);
        $extension = strtolower($file_info['extension']);

        if (file_exists($file_info['dirname'] . '/' . $file_name . '.' . $extension)) {
            $file_name = $file_name . '-' . time();
        }

        $file_name = $file_name . '.' . $extension;

        $targetPath = Yii::getAlias($preset['cacheIn']);
        $targetFile = $targetPath . '/' . $file_name;
        if (!file_exists($targetPath)) {
            mkdir($targetPath, 0777, true);
        } // mkdir recursive

        copy($source, $targetFile);
        chmod($targetFile, 0666);

        return $targetFile;
    }

    public function createAbsoluteUrl($presetName, $file, $options = array())
    {
        return Yii::$app->request->getHostInfo() . $this->createUrl($presetName,
            $file, $options);
    }

    public static function cyrillicToLatin(
        $text,
        $toLowCase = true,
        $maxlength = 100
    ) {
        $text = implode(array_slice(explode('<br>',
                wordwrap(trim(strip_tags(html_entity_decode($text))),
                    $maxlength, '<br>', false)), 0, 1));
        //$text = substr(, 0, $maxlength);

        foreach (self::$_matrix as $from => $to) {
            $text = mb_eregi_replace($from, $to, $text);
        }

        // Optionally convert to lower case.
        if ($toLowCase) {
            $text = strtolower($text);
        }

        return $text;
    }

    public static function getSize($file)
    {
        $cmd = "identify -format \"%w|%h|%k\" " . escapeshellarg($file) . " 2>&1";
        $returnVal = 0;
        $output = array();
        exec($cmd, $output, $returnVal);
        if ($returnVal == 0 && count($output) == 1) {
            $imageSizes = explode('|', $output[0]);

            return $imageSizes;
        }
    }
}