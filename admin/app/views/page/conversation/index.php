<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Các cuộc hội thoại</h5>
                        </div>
                    </div>
                </div>
                <?php if (isset($_SESSION['success'])) : ?>
                    <div class="m-3 alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                        <span class="alert-text text-white">
                            <?php echo isset($_SESSION['success']) ? $_SESSION['success'] : ""; ?>
                        </span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($conversations)) : ?>
                    <?php foreach ($conversations as $conversation) : ?>
                        <?php
                        $lastMessage = $conversation['last_message'];
                        $formattedDateTime = $lastMessage ? date("l, Y-m-d H:i:s", strtotime($lastMessage['created_at'])) : '';
                        ?>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="card shadow-none border-1 rounded mx-4 my-2 p-2 position-relative">
                                <a href="<?php echo URL_APP . '/conversation/detail/' . $conversation['conversation_id'] ?>">
                                    <div class="d-flex justify-content-between fs-6">
                                        <span class="fw-bolder text-black">
                                            <?php echo !isset($conversation['customer_id']) ? 'Ẩn danh' : $conversation['customer_name']; ?>
                                        </span>
                                        <span class="fw-bolder text-black-50">
                                            <?php echo $formattedDateTime; ?>
                                        </span>
                                    </div>
                                    <?php if ($lastMessage) : ?>
                                        <div class="d-flex flex-wrap">
                                            <?php echo $lastMessage['content']; ?>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="card-body">
                        <p class="text-center">Không có cuộc hội thoại nào</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>