<?php
/**
 * @link http://pagination.ru/
 * @author Vasiliy Makogon, makogon.vs@gmail.com
 */
class Krugozor_Pagination_Manager
{
    /**
     * Максимальное количество записей из СУБД,
     * которое необходимо выводить на одной странице.
     * Один из аргументов конструктора.
     *
     * @var int
     */
    private $limit;

    /**
     * Количество ссылок на страницы, выводящихся
     * между ссылками-метками пагинатора << и >>.
     * Фактически, это количество ссылок с числовыми
     * индексами в ссылочном блоке.
     *
     * @var int
     */
    private $link_count;

    /**
     * Номер текущей страницы.
     *
     * @var int
     */
    private $current_page;

    /**
     * Номер текущего сепаратора.
     *
     * @var int
     */
    private $current_sep;

    /**
     * Начальное значение для SQL-оператора LIMIT.
     *
     * @var int
     */
    private $start_limit;

    /**
     * Конечное значение для SQL-оператора LIMIT.
     *
     * @var int
     */
    private $stop_limit;

    /**
     * Общее количество записей в таблице БД, участвующих
     * в вычислениях и формировании данных для пагинатора.
     *
     * @var int
     */
    private $total_rows;

    /**
     * Количество страниц пагинатора, которое получится, если на одну страницу
     * необходимо выводить $this->limit записей из базы.
     *
     * @var int
     */
    private $total_pages;

    /**
     * Количество ссылочных блоков, на которые будет разделена БД.
     *
     * @var int
     */
    private $total_blocks;

    /**
     * Имя переменной из Request, значение которой будет указывать страницу.
     *
     * @var int
     */
    private $page_var_name;

    /**
     * Имя переменной из Request, значение которой будет указывать блок страниц (сепаратор).
     *
     * @var int
     */
    private $separator_var_name;

    /**
     * @param int $limit - количество записей из таблицы СУБД на страницу
     * @param int $link_count - количество ссылок на страницы между ссылками пагинатора, т.е.:
     *                          «««  ««  «  $link_count  »  »»  »»»
     * @param mixed $request Krugozor_Http_Request|$_REQUEST - объект запроса Krugozor_Http_Request или один из
     *                                                         суперглобальных массивов $_REQUEST, $_GET или $_POST.
     * @param string $page_var_name - имя ключа переменной из запроса, указывающей страницу для открытия.
     * @param string $separator_var_name - имя ключа переменной из запроса, указывающей блок страниц (сепаратор).
     * @return void
     */
    public function __construct($limit = 10, $link_count = 10, $request, $page_var_name = 'page_name', $separator_var_name = 'sep')
    {
        $this->limit = (int) $limit;
        $this->link_count = (int) $link_count;

        $this->page_var_name = (string) $page_var_name;
        $this->separator_var_name = (string) $separator_var_name;

        // Для фреймворка.
        if (is_object($request) && $request instanceof Krugozor_Http_Request)
        {
            $this->current_sep  = $request->getRequest($separator_var_name, 'decimal') ?: 1;
            $this->current_page = $request->getRequest($page_var_name, 'decimal') ?: ($this->current_sep - 1) * $this->link_count + 1;
        }
        // Для внедрения в любой сторонний код.
        else if (is_array($request))
        {
            $this->current_sep = !empty($request[$separator_var_name]) && is_numeric($request[$separator_var_name])
                                 ? intval($request[$separator_var_name])
                                 : 1;

            $this->current_page = !empty($request[$page_var_name]) && is_numeric($request[$page_var_name])
                                  ? intval($request[$page_var_name])
                                  : ($this->current_sep - 1) * $this->link_count + 1;
        }

        $this->start_limit = ($this->current_page - 1) * $this->limit;
        $this->stop_limit  = $this->limit;
    }

    /**
     * Возвращает начальное значение для SQL-оператора LIMIT.
     *
     * @param void
     * @return int
     */
    public function getStartLimit()
    {
        return $this->start_limit;
    }

    /**
     * Возвращает конечное значение для SQL-оператора LIMIT.
     *
     * @param void
     * @return int
     */
    public function getStopLimit()
    {
        return $this->stop_limit;
    }

    /**
     * Возвращает общее количество записей.
     *
     * @param void
     * @return int
     */
    public function getCount()
    {
        return $this->total_rows;
    }

    /**
     * Принимает числовое значение - общее количество записей в базе,
     * а также вычисляет все необходимые переменные для формирования строки навигации.
     *
     * Я пытался рефакторить алгоритм данного метода, но качественно сделать этого не удалось
     * в виду давности написания данного класса. Короче говоря, я сам уже не в состоянии понять, как
     * это работает. Но это работает!
     *
     * @param int
     * @return void
     */
    public function setCount($total_rows)
    {
        $this->total_rows = intval($total_rows);
        $this->total_pages = ceil($this->total_rows/$this->limit);
        $this->total_blocks = ceil($this->total_pages/$this->link_count);

        // Если количество блоков больше всех страниц, то
        // за количество блоков берём количество всех страниц.
        $this->total_blocks = ($this->total_blocks > $this->total_pages) ? $this->total_pages : $this->total_blocks;

        // Основной массив значений для вывода в шаблоне.
        $this->table = array();

        $k = ($this->current_sep - 1) * $this->link_count + 1;

            for ($i = $k; $i < $this->link_count + $k && $i <= $this->total_pages; $i++)
            {
                $temp = ($this->total_rows - (($i-1) * $this->limit));
                $temp2 = ($temp - $this->limit > 0) ? $temp - $this->limit + 1 : 1;

                $temp3 = ($this->limit * ($i - 1)) + 1;
                $temp4 = $i * $this->limit  > $this->total_rows ? $this->total_rows : $i * $this->limit;

                $this->table[] = array
                (
                    'page_name' => $i,
                    'separator' => $this->current_sep,
                    'decrement_anhor' => ($temp == $temp2 ? $temp : $temp . ' - ' . $temp2),
                    'increment_anhor' => ($temp3 == $temp4 ? $temp3 : $temp3 . ' - ' . $temp4)
                );
            }

        return $this;
    }

    /**
     * Возвращает число для начала отсчёта записей при декрементной пагинации.
     * В цикле, при выводе записей, данное число нужно декрементировать при
     * каждой итерации цикла.
     *
     * @param void
     * @return int
     */
    public function getAutodecrementNum()
    {
        return $this->total_rows - $this->start_limit;
    }

    /**
     * Возвращает число для начала отсчёта записей при инкрементной пагинации.
     * В цикле, при выводе записей, данное число нужно инкрементировать при
     * каждой итерации цикла.
     *
     * @param void
     * @return int
     */
    public function getAutoincrementNum()
    {
        return $this->limit * ($this->current_page-1) + 1;
    }

    /**
     * Возвращает номер сепаратора для формирования ссылки (««).
     *
     * @param void
     * @return int
     */
    public function getPreviousBlockSeparator()
    {
        return $this->current_sep - 1 ?: 0;
    }

    /**
     * Возвращает номер сепаратора для формирования ссылки (»»).
     *
     * @param void
     * @return int
     */
    public function getNextBlockSeparator()
    {
        return $this->current_sep < $this->total_blocks ? $this->current_sep + 1 : 0;
    }

    /**
     * Возвращает номер сепаратора для формирования ссылки (»»»).
     *
     * @param void
     * @return int
     */
    public function getLastSeparator()
    {
        return $this->total_blocks;
    }

    /**
     * Возвращает номер страницы для формирования ссылки (»»»).
     *
     * @param void
     * @return int
     */
    public function getLastPage()
    {
        return $this->total_pages;
    }

    /**
     * Возвращает многомерный массив для цикла вывода в шаблоне (см. шаблон).
     *
     * @param void
     * @return array
     */
    public function getTemplateData()
    {
        return $this->table;
    }

    /**
     * Возвращает номер текущей страницы.
     *
     * @param void
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->current_page;
    }

    /**
     * Возвращает номер текущего сепаратора.
     *
     * @param void
     * @return int
     */
    public function getCurrentSeparator()
    {
        return $this->current_sep;
    }

    /**
     * Возвращает номер сепаратора для формирования ссылки («).
     *
     * @param void
     * @return int
     */
    public function getPreviousPageSeparator()
    {
        // Текущий сепаратор, определённый програмно
        $cs = ceil($this->current_page / $this->link_count);
        // Определяем сепаратор страницы current_page - 1
        $cs2 = ceil(($this->current_page - 1) / $this->link_count);

        // Если сепаратор страницы current_page - 1 меньше текущего сепаратора,
        // значит страница current_page - 1 относится к следующему блоку с сепаратором $cs2
        return $cs2 < $cs ? $cs2 : $cs;
    }

    /**
     * Возвращает номер сепаратора для формирования ссылки (»).
     *
     * @param void
     * @return int
     */
    public function getNextPageSeparator()
    {
        // Текущий сепаратор, определённый програмно.
        $cs = ceil($this->current_page / $this->link_count);
        // Определяемсепаратор страницы current_page + 1.
        $cs2 = ceil(($this->current_page + 1) / $this->link_count);

        // Если сепаратор страницы current_page + 1 больше текущего сепаратора,
        // значит страница current_page + 1 относится к следующему блоку с сепаратором $cs2.
        return $cs2 > $cs ? $cs2 : $cs;
    }

    /**
     * Возвращает номер страницы для формирования ссылки («).
     *
     * @param void
     * @return int
     */
    public function getPreviousPage()
    {
        return $this->current_page - 1 ?: 0;
    }

    /**
     * Возвращает номер страницы для формирования ссылки (««).
     *
     * @param void
     * @return int
     */
    public function getPageForPreviousBlock()
    {
        return $this->current_page - ($this->current_page % $this->link_count ?: $this->link_count);
    }

    /**
     * Возвращает номер страницы для формирования ссылки (»).
     *
     * @param void
     * @return int
     */
    public function getNextPage()
    {
        return $this->current_page < $this->total_pages ? $this->current_page + 1 : 0;
    }

    /**
     * Возвращает имя переменной из запроса, содержащей номер сепаратора.
     *
     * @param void
     * @return string
     */
    public function getSeparatorName()
    {
        return $this->separator_var_name;
    }

    /**
     * Возвращает имя переменной из запроса, содержащей номер страницы.
     *
     * @param void
     * @return string
     */
    public function getPageName()
    {
        return $this->page_var_name;
    }
}