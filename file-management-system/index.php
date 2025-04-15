<?php
if(isset($_GET["path"]) && $_GET["path"] != "") {
    $fetch_path = $_GET["path"];

    $path_arr_temp = explode('\\', $fetch_path);
    array_pop($path_arr_temp);
    $prev_path = implode("\\", $path_arr_temp);
    
    $path_arr = explode('\\', $fetch_path);
    $folder_path = implode("/", $path_arr);
    $next_path = implode("\\", $path_arr);

    $sub_path = "?path=$fetch_path&";

    $subpath = true;
    define('DIR_PATH', './datas/'.$folder_path.'/');
} else {
    $subpath = false;
    $next_path = "";
    define('DIR_PATH', './datas/');
}


if(isset($_GET["rm"]) && $_GET["rm"] != "") {
    $rm_name = (string) $_GET["rm"];

    if(is_dir($rm_name)) {
        if(!@rmdir($rm_name)) {
            echo "<script>alert('Remove inner files & folders first before deleting directory');</script>";
        }
    } elseif(is_file($rm_name)) {
        unlink($rm_name);
    }
}


if(isset($_POST["createFName"]) && $_POST["createFName"] != "") {
    $folderName = (string) $_POST["createFName"];

    mkdir(DIR_PATH."".$folderName);
}



$file_icons = [
    "folder" => "fa-folder",
    "document" => [
        "pdf" => "fa-file-pdf",
        "word" => "fa-file-word",
        "excel" => "fa-file-excel",
        "powerpoint" => "fa-file-powerpoint",
        "text" => "fa-file-text",
    ],
    "file" => [
        "code" => "fa-file-code",
        "csv" => "fa-file-csv",
    ],
    "image" => "fa-file-image",
    "audio" => [
        "audio" => "fa-file-audio",
    ],
    "video" => [
        "video" => "fa-file-video",
    ],
];


function sizeConvert($size) {
    $kb = round(($size / 1024), 2);
    $mb = round(($size / (1024 * 1024)), 2);
    $gb = round(($size / (1024 * 1024 * 1024)), 2);

    if($mb > 1) {
        return $mb." MB";
    } elseif($gb > 1) {
        return $gb." GB";
    } else {
        return $kb." KB";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP File Manager</title>
    
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <i class="fas fa-file-alt"></i>
                <h1>File Manager</h1>
            </div>
            <div class="user-info">
                <!-- <span>admin@example.com</span>
                <button class="logout-btn">Logout</button> -->
            </div>
        </header>

        <div class="main-content">
            <div class="sidebar">
                <h3 class="sidebar-title">Storage</h3>
                <div class="storage-info">
                    <p>
                        <span>Used Space:</span>
                        <span>650 MB / 1 GB</span>
                    </p>
                    <div class="progress-bar">
                        <div class="progress-bar-fill"></div>
                    </div>
                </div>

                <h3 class="sidebar-title">Navigation</h3>
                <ul class="nav-menu">
                    <li><a href="#" class="active"><i class="fas fa-folder"></i> All Files</a></li>
                    <li><a href="#"><i class="fas fa-image"></i> Images</a></li>
                    <li><a href="#"><i class="fas fa-file-alt"></i> Documents</a></li>
                    <li><a href="#"><i class="fas fa-file-archive"></i> Archives</a></li>
                    <li><a href="#"><i class="fas fa-trash"></i> Trash</a></li>
                    <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
                </ul>
            </div>

            <div class="content">
                <div class="toolbar">
                    <div class="path-indicator">
                        <span>Home</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Documents</span>
                    </div>
                    <div class="action-buttons">
                        <a href="#createFolder" class="btn btn-primary"><i class="fas fa-folder-plus"></i> New Folder</a>
                        <a href="#uploadModal" class="btn btn-success"><i class="fas fa-upload"></i> Upload</a>
                    </div>
                </div>

                <div class="search-box">
                    <input type="text" placeholder="Search files and folders...">
                    <button><i class="fas fa-search"></i></button>
                </div>

                <div class="file-list">
                    <div class="file-list-header">
                        <div>Name</div>
                        <div>Size</div>
                        <div>Modified</div>
                        <div>Actions</div>
                    </div>

                    <?php
                    if($subpath) {
                    ?>
                        <div class="file-item">
                            <div class="file-name">
                                <a href="?path=<?= $prev_path; ?>">
                                    <div class="file-icon"><i class="fas fa-arrow-left-long"></i></div>
                                    <span>Parent Directory</span>
                                </a>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                    <?php
                    $allfiles = scandir(DIR_PATH);
                    if($allfiles) {
                        $all_dirs = [];
                        $all_files = [];

                        foreach($allfiles as $file) {
                            if($file != "." && $file != "..") {
                                $full_path = DIR_PATH."".$file;
                                $file_data = pathinfo($full_path);
                                if(is_file($full_path)) {
                                    $file_data["file_type"] = "file";
                                    $file_data["full_path"] = $full_path;
                                    $all_files[$file_data["filename"]] = $file_data;
                                } elseif(is_dir($full_path)) {
                                    $file_data["file_type"] = "folder";
                                    $file_data["full_path"] = $full_path;
                                    $all_dirs[$file_data["filename"]] = $file_data;
                                }
                            }
                        }

                        ksort($all_dirs);
                        ksort($all_files);
                        $allfiles = array_merge($all_dirs, $all_files);

                        foreach($allfiles as $file_data) {
                            $file_name = $file_data['basename'];
                            $file_type = $file_data['file_type'];
                            $file_ext = $file_data['extension'] ?? '';
                            $full_path = $file_data['full_path'];

                            if($file_type == "file") {
                                $file_size = filesize($full_path);
                                if($file_ext == "jpeg" || $file_ext == "jpg" || $file_ext == "gif" || $file_ext == "png") {
                                    $file_type = "image";
                                    $file_faicons = $file_icons["image"];
                                } elseif($file_ext == "audio") {
                                    $file_type = "audio";
                                    $file_faicons = $file_icons["audio"]["audio"];
                                } elseif($file_ext == "video") {
                                    $file_type = "video";
                                    $file_faicons = $file_icons["video"]["video"];
                                } elseif($file_ext == "code" || $file_ext == "csv") {
                                    $file_type = "file";
                                    if($file_ext == "code") {
                                        $file_faicons = $file_icons["file"]["code"];
                                    } elseif($file_ext == "csv") {
                                        $file_faicons = $file_icons["file"]["csv"];
                                    }
                                } elseif($file_ext == "pdf" || $file_ext == "word" || $file_ext == "excel" || $file_ext == "powerpoint" || $file_ext == "text") {
                                    $file_type = "document";
                                    if($file_ext == "pdf") {
                                        $file_faicons = $file_icons["document"]["pdf"];
                                    } elseif($file_ext == "word") {
                                        $file_faicons = $file_icons["document"]["word"];
                                    } elseif($file_ext == "excel") {
                                        $file_faicons = $file_icons["document"]["excel"];
                                    } elseif($file_ext == "powerpoint") {
                                        $file_faicons = $file_icons["document"]["powerpoint"];
                                    } else {
                                        $file_faicons = $file_icons["document"]["text"];
                                    }
                                } else {
                                    $file_type = "document";
                                    $file_faicons = $file_icons["document"]["text"];
                                }
                            ?>
                                <div class="file-item <?= $file_type; ?>">
                                    <div class="file-name">
                                        <div class="file-icon"><i class="fas <?= $file_faicons; ?>"></i></div>
                                        <span><?= $file_name; ?></span>
                                    </div>
                                    <div><?= sizeConvert($file_size); ?></div>
                                    <div><?= date("M d, Y", filemtime($full_path)); ?></div>
                                    <div class="file-actions">
                                        <a class="action-btn" href="<?= $sub_path ?? "?"; ?>rn=<?= $full_path; ?>"><i class="fas fa-pen"></i></a>
                                        <a class="action-btn" href="<?= $sub_path ?? "?"; ?>rm=<?= $full_path; ?>"><i class="fas fa-trash"></i></a>
                                        <a class="action-btn" href="<?= $sub_path ?? "?"; ?>cp=<?= $full_path; ?>"><i class="fas fa-copy"></i></a>
                                        <a class="action-btn" href="<?= $sub_path ?? "?"; ?>mv=<?= $full_path; ?>"><i class="fas fa-file-export"></i></a>
                                    </div>
                                </div>
                            <?php
                            } else {
                                $file_type = 'folder';
                                $file_faicons = $file_icons["folder"];

                                $subfile_name = $file_name;
                                if($subpath) {
                                    $subfile_name = $next_path."\\".$file_name;
                                }
                            ?>
                                <div class="file-item <?= $file_type; ?>">
                                    <div class="file-name">
                                        <a href="?path=<?= $subfile_name; ?>">
                                            <div class="file-icon"><i class="fas <?= $file_faicons; ?>"></i></div>
                                            <span><?= $file_name; ?></span>
                                        </a>
                                    </div>
                                    <div>--</div>
                                    <div><?= date("M d, Y", filemtime($full_path)); ?></div>
                                    <div class="file-actions">
                                        <a class="action-btn" href="<?= $sub_path ?? "?"; ?>rn=<?= $full_path; ?>"><i class="fas fa-pen"></i></a>
                                        <a class="action-btn" href="<?= $sub_path ?? "?"; ?>rm=<?= $full_path; ?>"><i class="fas fa-trash"></i></a>
                                        <a class="action-btn" href="<?= $sub_path ?? "?"; ?>cp=<?= $full_path; ?>"><i class="fas fa-copy"></i></a>
                                        <a class="action-btn" href="<?= $sub_path ?? "?"; ?>mv=<?= $full_path; ?>"><i class="fas fa-file-export"></i></a>
                                    </div>
                                </div>
                            <?php
                            }
                        }
                    }
                    ?>   

                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="createFolder" class="upload-modal">
        <form action="<?= ($subpath) ? $sub_path : "./" ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Create Folder</h3>
                </div>
                <div class="modal-body">
                    <input type="text" placeholder="Folder Name" name="createFName" required>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-primary">Cancel</a>
                    <button type="submit" class="btn btn-success">Create Folder</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="upload-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Upload Files</h3>
                <a href="#" class="close-btn">&times;</a>
            </div>
            <div class="modal-body">
                <div class="upload-area">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Drag & drop files or <span>Browse</span></p>
                    <p class="small">Supported formats: JPG, PNG, PDF, DOCX, ZIP (Max: 10MB)</p>
                    <input type="file" multiple>
                </div>

                <ul class="upload-list">
                    <li class="upload-item">
                        <div class="upload-item-info">
                            <i class="fas fa-file-pdf"></i>
                            <span>project_proposal.pdf</span>
                        </div>
                        <div class="upload-progress">
                            <div class="upload-progress-bar"></div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary">Cancel</a>
                <button class="btn btn-success">Upload Files</button>
            </div>
        </div>
    </div>

    <script>
        // Basic interactive elements for the demo
        document.addEventListener('DOMContentLoaded', function() {

            // Close modal when clicking outside
            const cf_modal = document.getElementById('createFolder');
            if (cf_modal) {
                window.addEventListener('click', function(e) {
                    if (e.target === cf_modal) {
                        window.location.hash = '';
                    }
                });
            }

            // Close modal when clicking outside
            const modal = document.getElementById('uploadModal');
            if (modal) {
                window.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        window.location.hash = '';
                    }
                });
            }

            // Simulate file upload by clicking on the upload area
            const uploadArea = document.querySelector('.upload-area');
            const fileInput = document.querySelector('.upload-area input');
            
            if (uploadArea && fileInput) {
                uploadArea.addEventListener('click', function() {
                    fileInput.click();
                });
                
                fileInput.addEventListener('change', function() {
                    // In a real app, this would handle file uploads
                    alert('File selected: ' + this.files[0].name);
                });
            }
        });
    </script>
</body>
</html>