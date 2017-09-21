 <?php

class Picture {
	
	public $quality = 80;
    protected $image, $filename, $original_info, $width, $height, $imagestring, $mimetype;
	   
	   
	   function __construct($filename = null, $width = null, $height = null, $color = null) {
        if ($filename) {
            $this->load($filename);
        } elseif ($width) {
            $this->create($width, $height, $color);
        }
        return $this;
    }
	   function __destruct() {
        if( $this->image !== null && get_resource_type($this->image) === 'gd' ) {
            imagedestroy($this->image);
        }
    }
	  function create($width, $height = null, $color = null) {
        $height = $height ?: $width;
        $this->width = $width;
        $this->height = $height;
        $this->image = imagecreatetruecolor($width, $height);
        $this->original_info = array(
            'width' => $width,
            'height' => $height,
            'orientation' => $this->get_orientation(),
            'exif' => null,
            'format' => 'png',
            'mime' => 'image/png'
        );
        if ($color) {
            $this->fill($color);
        }
        return $this;
    }
   function load($filename) {
        // Require GD library
        if (!extension_loaded('gd')) {
            throw new Exception('Required extension GD is not loaded.');
        }
        $this->filename = $filename;
        return $this->get_meta_data();
    }
	
	function best_fit($max_width, $max_height) {
        // If it already fits, there's nothing to do
        if ($this->width <= $max_width && $this->height <= $max_height) {
            return $this;
        }
        // Determine aspect ratio
        $aspect_ratio = $this->height / $this->width;
        // Make width fit into new dimensions
        if ($this->width > $max_width) {
            $width = $max_width;
            $height = $width * $aspect_ratio;
        } else {
            $width = $this->width;
            $height = $this->height;
        }
        // Make height fit into new dimensions
        if ($height > $max_height) {
            $height = $max_height;
            $width = $height / $aspect_ratio;
        }
        return $this->resize($width, $height);
    }
	
	
	  function resize($width, $height) {
        // Generate new GD image
        $new = imagecreatetruecolor($width, $height);
        if( $this->original_info['format'] === 'gif' ) {
            // Preserve transparency in GIFs
            $transparent_index = imagecolortransparent($this->image);
            $palletsize = imagecolorstotal($this->image);
            if ($transparent_index >= 0 && $transparent_index < $palletsize) {
                $transparent_color = imagecolorsforindex($this->image, $transparent_index);
                $transparent_index = imagecolorallocate($new, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                imagefill($new, 0, 0, $transparent_index);
                imagecolortransparent($new, $transparent_index);
            }
        } else {
            // Preserve transparency in PNGs (benign for JPEGs)
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }
        // Resize
        imagecopyresampled($new, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
        // Update meta data
        $this->width = $width;
        $this->height = $height;
        $this->image = $new;
        return $this;
    }
	
	   function save($filename = null, $quality = null, $format = null) {
        // Determine quality, filename, and format
        $filename = $filename ?: $this->filename;
        if( !$format )
            $format = $this->file_ext($filename) ?: $this->original_info['format'];
        list( $mimetype, $imagestring ) = $this->generate( $format, $quality );
        // Save the image
        $result = file_put_contents( $filename, $imagestring );
        if (!$result)
            throw new Exception('Unable to save image: ' . $filename);
        return $this;
    }
	function get_orientation() {
        if (imagesx($this->image) > imagesy($this->image)) {
            return 'landscape';
        }
        if (imagesx($this->image) < imagesy($this->image)) {
            return 'portrait';
        }
        return 'square';
    }
	
	   protected function file_ext($filename) {
        if (!preg_match('/\./', $filename)) {
            return '';
        }
        return preg_replace('/^.*\./', '', $filename);
    }
	
	      protected function generate($format = null, $quality = null) {
        // Determine quality
        $quality = $quality ?: $this->quality;
        // Determine mimetype
        switch (strtolower($format)) {
            case 'gif':
                $mimetype = 'image/gif';
                break;
            case 'jpeg':
            case 'jpg':
                imageinterlace($this->image, true);
                $mimetype = 'image/jpeg';
                break;
            case 'png':
                $mimetype = 'image/png';
                break;
            default:
                $info = (empty($this->imagestring)) ? getimagesize($this->filename) : getimagesizefromstring($this->imagestring);
                $mimetype = $info['mime'];
                unset($info);
                break;
        }
        // Sets the image data
        ob_start();
        switch ($mimetype) {
            case 'image/gif':
                imagegif($this->image);
                break;
            case 'image/jpeg':
                imagejpeg($this->image, null, round($quality));
                break;
            case 'image/png':
                imagepng($this->image, null, round(9 * $quality / 100));
                break;
            default:
                throw new Exception('Unsupported image format: '.$this->filename);
                break;
        }
        $imagestring = ob_get_contents();
        ob_end_clean();
        return array($mimetype, $imagestring);
    }
	
	   protected function get_meta_data() {
        //gather meta data
        if(empty($this->imagestring)) {
            $info = getimagesize($this->filename);
            switch ($info['mime']) {
                case 'image/gif':
                    $this->image = imagecreatefromgif($this->filename);
                    break;
                case 'image/jpeg':
                    $this->image = imagecreatefromjpeg($this->filename);
                    break;
                case 'image/png':
                    $this->image = imagecreatefrompng($this->filename);
                    break;
                default:
                    throw new Exception('Invalid image: '.$this->filename);
                    break;
            }
        } elseif (function_exists('getimagesizefromstring')) {
            $info = getimagesizefromstring($this->imagestring);
        } else {
            throw new Exception('PHP 5.4 is required to use method getimagesizefromstring');
        }
        $this->original_info = array(
            'width' => $info[0],
            'height' => $info[1],
            'orientation' => $this->get_orientation(),
            'exif' => function_exists('exif_read_data') && $info['mime'] === 'image/jpeg' && $this->imagestring === null ? $this->exif = @exif_read_data($this->filename) : null,
            'format' => preg_replace('/^image\//', '', $info['mime']),
            'mime' => $info['mime']
        );
        $this->width = $info[0];
        $this->height = $info[1];
        imagesavealpha($this->image, true);
        imagealphablending($this->image, true);
        return $this;
    }
	
	 function get_original_info() {
        return $this->original_info;
    }


	
	
	}