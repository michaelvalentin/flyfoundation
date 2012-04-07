<?php
namespace Flyf\Models\Abstracts\RawModel;

/**
 * !TODO Rebuild the Resource... It is kind of an optimization (compared to just loading objects), so it can wait a bit, but on the other hand it's is NECESSARY to display fx. a list of blog-posts or products.. 
 * 
 * The Resource is a convenient way to fetch multiple
 * instances of a model. The Resource is designed to
 * inheritance, but can be used as an instance on its own.
 *
 * The Resource works by setting a various range of parameters
 * to be taking into account when creating the models. This
 * Resource offers a range of standard parameters that can be
 * set, but for specialization one should inherit a model-specific
 * Resource.
 *
 * The Resource is able to build/instantiate the models given the
 * parameters set, like a "model factory".
 *
 * The Resource uses a QueryBuilder to assemble and execute queries.
 *
 * @example
 * $resource = Page::Resource();
 * $resource->SetLimit(3); // we only wants 3 models, this is a method defined in Resource
 * $resource->SetUrl('blog'); // this could be a method defined in a child of Resource
 * $pages = $resource->Build(); // Fetches the models given the parameters
 * 
 * @author Michael Valentin <mv@signifly.com>
 * @version 2012-01-06
 * @dependencies QueryBuilder
 */
class Resource {
	// The query builer instance used to assembling and querying
	protected $QueryBuilder;
	// The model which the Resource should construct instances of
	private $model;

	/**
	 * Constructing the Resource with the an instance of the model 
	 * to be constructed as parameter. Initializes a QueryBuilder
	 * and sets the table and the fields to be used on the QueryBuilder.
	 *
	 * @param $model (the model to be constructed)
	 */
	public function __construct($model) {
		$this->model = get_class($model);
		
		$this->QueryBuilder = new \Flyf\Database\QueryBuilder();

		$this->QueryBuilder->SetTable($model::GetTable());
		$this->QueryBuilder->SetFields($model::getFields());
	}

	/**
	 * How many models should ultimately be fetched.
	 *
	 * @param integer $limit
	 */
	public function SetLimit($limit) {
		$this->QueryBuilder->setLimit($limit);
	}

	/**
	 * From which offset should the models be fetched.
	 *
	 * @param integer $offset
	 */
	public function SetOffset($offset) {
		$this->QueryBuilder->setOffset($offset);
	}
	/**
	 * In which order should the models be fetched.
	 *
	 * @param string $order
	 * @param string $dir
	 */
	public function SetOrder($order, $dir) {
		$this->QueryBuilder->addOrder($order, $dir);
	}

	/**
	 * How many models where fetched. Does only return
	 * a meaningfull result after Build() has been called.
	 *
	 * @return integer
	 */
	public function GetCount() {
		return $this->QueryBuilder->GetCount();
	}

	/**
	 * How many models could be fetched (without limit and
	 * offset parameters). Does only return a meaningfull 
	 * result after Build() has been called.
	 *
	 * @return integer
	 */
	public function GetCountTotal() {
		return $this->QueryBuilder->GetCountTotal();
	}

	/**
	 * Executes a query given the parameters set by using the
	 * query builer. From the results of the execution it 
	 * creates the given number of model instances and returns
	 * them in an array.
	 * 
	 * @return array (an array of models given the specified model)
	 */
	public function Build() {
		$objects = array();
		$class = $this->model;
		
		if (count($dataset = $this->QueryBuilder->Execute())) {
			foreach ($dataset as $data) {
				$objects[] = $class::Create($data);
			}
		}

		return $objects;
	}
}