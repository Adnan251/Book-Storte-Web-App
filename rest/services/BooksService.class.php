<?php

require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/BooksDao.class.php';
require_once __DIR__.'/../dao/WritersDao.class.php';
require_once __DIR__.'/../dao/BooksAndWritersDao.class.php';
require_once __DIR__.'/../dao/PublisherDao.class.php';

class BooksService extends BaseService
{
    private $writerDao;
    private $publisherDao;
    private $booksAndWritersDao;

    public function __construct()
    {
        $this->writerDao = WritersDao::get_instance();
        $this->publisherDao = PublisherDao::get_instance();
        $this->booksAndWritersDao = BooksAndWritersDao::get_instance();
        parent::__construct(BooksDao::get_instance());
    }

    public function get_books_with_writer_names()
    {
        $data = $this->dao->get_books_with_writer_names();
        return $data;
    }

    public function get_by_id_with_writer_names($id)
    {
        return $this->dao->get_by_id_with_writer_names($id);
    }

    public function search_book($name)
    {
        return $this->dao->search_book($name);
    }

    public function get_book_by_writer($name, $lastName)
    {
        return $this->dao->get_book_by_writer($name, $lastName);
    }

    public function find_book($book)
    {
        return $this->dao->find_book($book);
    }


    public function add_book_and_writer($bookDescriptor)
    {
        if($this->find_book($bookDescriptor)['0']['found']!=0) {
            return null;
        } else {
            $writer = $this->writerDao->get_writer_by_names($bookDescriptor['Writer_Last_Name'], $bookDescriptor['Writer_Name']);
            $publisher = $this->publisherDao->get_by_publisher_name($bookDescriptor['name']);
            // no writer in DB add it
            if (!isset($writer['id'])) {
                $writer = $this->writerDao->add(['Writer_Name' => $bookDescriptor['Writer_Name'], 'Writer_Last_Name' => $bookDescriptor['Writer_Last_Name']]);
            }
            // no publisher in DB add it
            if (!isset($publisher['id'])) {
                $publisher = $this->publisherDao->add(['name' => $bookDescriptor['name']]);
            }

            $addedBook = $this->dao->add(['Book_Name' => $bookDescriptor['Book_Name'],
                            'Publisher' => $publisher['id'],
                            'Year_of_publishing' => $bookDescriptor['Year_of_publishing'],
                            'Book_price' => $bookDescriptor['Book_price'],
                            'In_inventory' => $bookDescriptor['In_inventory']]);

            $baw = $this->booksAndWritersDao->add(['bookid'=>$addedBook['id'],'writerid'=>$writer['id']]);
            $book = $this->dao->get_by_id_with_writer_names($addedBook['id']);

            return $book;
        }
    }

    public function update_the_books($id)
    {
        $book = $this->dao->get_by_id($id);
        if($book == null) {
            return null;
        } else {
            if($book['is_available'] < 1) {
                $updated = $this->dao->update($id, ['Book_Name' => $book['Book_Name'],
                                          'Publisher' => $book['Publisher'],
                                          'Year_of_publishing' => $book['Year_of_publishing'],
                                          'Book_price' => $book['Book_price'],
                                          'In_inventory' => $book['In_inventory'],
                                          'is_available' => 1]);
            } else {
                $updated = $this->dao->update($id, ['Book_Name' => $book['Book_Name'],
                                          'Publisher' => $book['Publisher'],
                                          'Year_of_publishing' => $book['Year_of_publishing'],
                                          'Book_price' => $book['Book_price'],
                                          'In_inventory' => $book['In_inventory'],
                                          'is_available' => 0 ]);
            }
            return $updated;
        }
    }

    public function update_book_and_writer($bookDescriptor, $id)
    {
        if($this->find_book($bookDescriptor)['0']['found']!=0) {
            return null;
        } else {
            $writer = $this->writerDao->get_writer_by_names($bookDescriptor['Writer_Last_Name'], $bookDescriptor['Writer_Name']);
            $publisher = $this->publisherDao->get_by_publisher_name($bookDescriptor['name']);

            // no writer in DB add it
            if (!isset($writer['id'])) {
                $writer = $this->writerDao->add(['Writer_Name' => $bookDescriptor['Writer_Name'], 'Writer_Last_Name' => $bookDescriptor['Writer_Last_Name']]);
            }
            // no publisher in DB add it
            if (!isset($publisher['id'])) {
                $publisher = $this->publisherDao->add(['name' => $bookDescriptor['name']]);
            }

            $this->dao->update($id, ['Book_Name' => $bookDescriptor['Book_Name'],
                                      'Publisher' => $publisher['id'],
                                      'Year_of_publishing' => $bookDescriptor['Year_of_publishing'],
                                      'Book_price' => $bookDescriptor['Book_price'],
                                      'In_inventory' => $bookDescriptor['In_inventory']]);


            $book = $this->dao->get_by_id_with_writer_names($id);

            $baw = $this->booksAndWritersDao->get_BaW($book['id']);

            $this->booksAndWritersDao->update($baw['id'], ['bookid'=>$book['id'],'writerid'=>$writer['id']]);

            return $book;
        }
    }
}
